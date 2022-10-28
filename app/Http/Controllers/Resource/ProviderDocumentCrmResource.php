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
use App\UserRequests;
use App\UserRequestPayment;
class ProviderDocumentCrmResource extends Controller
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
			
			
            return view('crm.providers.document.index', compact('Provider', 'ServiceTypes','ProviderService', 'pr_documents'));
        } catch (ModelNotFoundException $e) {
            
            return redirect()->route('crm.index');
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

        return redirect()->route('crm.provider.document.index', $provider)->with('flash_success', 'Driver service type updated successfully!');
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
			
            return view('crm.providers.document.edit', compact('Document'));
        } catch (Exception $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
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
                ->route('crm.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been approved.');
        } catch (Exception $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
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
                ->route('crm.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
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
                ->route('crm.provider.document.index', $provider)
                ->with('flash_success', 'Driver service has been deleted.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
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
			
            return view('crm.providers.document.create', compact('Document','Provider'));
        } catch (Exception $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
		
	}
	public function edit_provider_document_upload(Request $request, $provider,$id) {
		
	    try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
				throw new  Exception('Driver document not found!');
			}
			
            return view('crm.providers.document.editdata', compact('Document'));
        } catch (Exception $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
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
                ->route('crm.provider.document.index', $provider)->with('flash_success', 'Driver document has been inserted.');;
        
		
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
                ->route('crm.provider.document.index', $provider)
                ->with('flash_success', 'Driver document has been Updated.');
        } catch (Exception $e) {
            return redirect()
                ->route('crm.provider.document.index', $provider)
                ->with('flash_error', $e->getMessage() );
        }
        
		
	}
	
     public function approve($id)
    {
        try {
            $Provider = Provider::findOrFail($id);
            if($Provider->service) {
                $Provider->update(['status' => 'approved']);
                return back()->with('flash_success', "Provider Approved");
            } else {
                return redirect()->route('crm.provider.document.index', $id)->with('flash_error', "Driver has not been assigned a service type!");
            }
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function disapprove($id)
    {
        
        Provider::where('id',$id)->update(['status' => 'banned']);
        return back()->with('flash_success', "Driver Disapproved");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function request($id){

        try{

            $requests = UserRequests::where('user_requests.provider_id',$id)
                    ->RequestHistory()
                    ->get();

            return view('crm.request.index', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($id){
    
        try{

            $requests = UserRequests::where('provider_id',$id)
                        ->where('status','COMPLETED')
                        ->with('payment')
                        ->get();

            $rides = UserRequests::where('provider_id',$id)->with('payment')->orderBy('id','desc')->paginate(10);
            $cancel_rides = UserRequests::where('status','CANCELLED')->where('provider_id',$id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function($query) use($id) {
                                    $query->where('provider_id', $id );
                                })->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                               ))->get();

            $revenues = UserRequestPayment::sum('total');
            $commision = UserRequestPayment::sum('commision');
            $Joined = $Provider->created_at ? '- Joined '.$Provider->created_at->diffForHumans() : '';

            return view('crm.providers.statement', compact('rides','cancel_rides','revenue','revenues','commision'))
                        ->with('page',$Provider->first_name."'s Overall Statement ". $Joined);

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
}
