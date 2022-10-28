<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SendPushNotification;

use Exception;
use App\Provider;
use App\ServiceType;
use App\ProviderService;
use App\ProviderDocument;
use App\Document;
use Carbon\Carbon;


class ProviderDocumentResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $provider)
    {
       
        try {
			
            $Provider = Provider::findOrFail($provider);
            $ProviderService = ProviderService::where('provider_id',$provider)->with('service_type')->get();
            $ServiceTypes = ServiceType::all();
			
			$documents = Document::all();
			
			$ProviderDocuments = ProviderDocument::where('provider_id', $provider )->get();
			
			$pr_documents = [];
			if( $documents->count() ) {
				foreach( $documents as $doc ) {
					$status = 'MISSING';
					$url 	=	'';
					$expires_at 	=	'0000-00-00';
					if($ProviderDocuments->count() ) {
						foreach($ProviderDocuments as $pr_doc ) {
							if( $pr_doc->document_id == $doc->id ) {
								$status =	$pr_doc->status;
								$url	=	$pr_doc->url;
								$expires_at	= $pr_doc->expires_at;
							}
						}
					}
					
					$pr_documents[] =  [
						'id'			=> $doc->id,
						'name'			=> $doc->name,
						'type'			=> $doc->type,
						'expires_at'	=> isset($expires_at)? $expires_at: '0000-00-00',
						'status'		=> $status,
						'url'			=> $url,
						'provider_id'	=> $provider,
					];
				}
				
			}
			
			
            return view('admin.providers.document.index', compact('Provider', 'ServiceTypes','ProviderService', 'pr_documents'));
        } catch (ModelNotFoundException $e) {
            
            return redirect()->route('admin.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $provider)
    {
        $this->validate($request, [
                'service_type' => 'required|exists:service_types,id',
                'service_number' => 'required',
                'service_model' => 'required',
            ]);
        

        try {
            
            $ProviderService = ProviderService::where('provider_id', $provider)->firstOrFail();
            $ProviderService->update([
                    'service_type_id' => $request->service_type,
                    'status' => 'offline',
                    'service_number' => $request->service_number,
                    'service_model' => $request->service_model,
                ]);

            // Sending push to the provider
            (new SendPushNotification)->DocumentsVerfied($provider);

        } catch (ModelNotFoundException $e) {
            ProviderService::create([
                    'provider_id' => $provider,
                    'service_type_id' => $request->service_type,
                    'status' => 'offline',
                    'service_number' => $request->service_number,
                    'service_model' => $request->service_model,
                ]);
        }

        return redirect()->route('admin.provider.document.index', $provider)->with('flash_success', 'Driver vehicle type updated successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $provider, $id)
    {
        
        
        try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
				throw new  Exception('Driver document not found!');
			}
			
            return view('admin.providers.document.edit', compact('Document'));
        } catch (Exception $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
    }
    
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
	
	public function update(Request $request, $provider, $id)
    {
		
        try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
				throw new  Exception('Driver document not found!');
			}
				
				
            $Document->status = 'ACTIVE';
			$Document->save();

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been approved.');
        } catch (Exception $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($provider, $id)
    {
        try {

            $Document = ProviderDocument::where('provider_id', $provider)
                ->where('document_id', $id)
                ->firstOrFail();
            $Document->delete();

			$p_documents  = ProviderDocument::where('provider_id', $provider)->count();
			
			if( $p_documents == 0 ) {
				$Provider = Provider::where('id', $provider)->firstOrFail();
				$Provider->status = 'onboarding';
				$Provider->save();
			}
			
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', 'Driver not found!');
        }
    }

    /**
     * Delete the service type of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_destroy(Request $request, $provider, $id)
    {
        try {

            $ProviderService = ProviderService::where('provider_id', $provider)->firstOrFail();
            $ProviderService->delete();

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver service has been deleted.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', 'Driver service not found!');
        }
    }
    
    public function get_provider_document_upload(Request $request, $provider,$id) {
		
	    try {
			
            $Provider = Provider::where('id',$provider)->get();
		    $Document = Document::where('id',$id)->get();
			if( ! $Document ) {
				throw new  Exception('Driver document not found!');
			}
			
            return view('admin.providers.document.create', compact('Document','Provider'));
        } catch (Exception $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
		
	}
	public function edit_provider_document_upload(Request $request, $provider,$id) {
		
	    try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
				throw new  Exception('Driver document not found!');
			}
			
            return view('admin.providers.document.editdata', compact('Document'));
        } catch (Exception $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
		
	}


	public function provider_document_upload(Request $request,$provider ) {
		
	    
        $this->validate($request, [
                'document' 		=> 'required|mimes:jpg,jpeg,png,pdf',
				'expiry_date'   => 'required'
            ]);

        
       
            ProviderDocument::create([
                    'url' => $request->document->store('provider/documents'),
                    'provider_id' => $request->provider_id,
                    'document_id' => $request->document_id,
                    'status' => 'ASSESSING',
                    'expires_at' =>  Carbon::parse($request->expiry_date),
                ]);
        
        return redirect()
                ->route('admin.provider.document.index', $provider)->with('flash_success', 'Driver document has been inserted.');;
        
		
	}
	public function update_provider_document_upload(Request $request,$provider,$id ) {
		
       
        try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
			    
				throw new  Exception('Driver document not found!');
			}
			$Document->url = $request->document->store('provider/documents');
			
			$Document->expires_at = $request->expiry_date;
			$Document->status = 'ASSESSING';
			$Document->save();
			/*$Document->update([
                    //'url' => $request->document->store('provider/documents'),
                    'expires_at' => $request->expiry_date,
                    'status' => 'ASSESSING',
					
					]);*/

            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been Updated.');
        } catch (Exception $e) {
            return redirect()
                ->route('admin.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
        
		
	}
	
}
