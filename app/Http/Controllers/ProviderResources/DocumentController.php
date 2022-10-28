<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;
use App\Document;
use App\ProviderDocument;
use App\UserRequests;
use App\Provider;   

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $VehicleDocuments = Document::vehicle()->get();
        $DriverDocuments = Document::driver()->get();

        $Provider = \Auth::guard('provider')->user();

        $fully = UserRequests::where('provider_id',\Auth::guard('provider')->user()->id)
                ->with('payment','service_type')
                ->get();

        return view('provider.document.index', compact('DriverDocuments', 'VehicleDocuments', 'Provider','fully'));
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
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        
        try {
			
            $Document = ProviderDocument::where('provider_id', $provider)->where('document_id', $id)->first();
			
			if( ! $Document ) {
				throw new  Exception('Provider document not found!');
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'document' => 'mimes:jpg,jpeg,png,pdf',
            ]);

        try {
            
            $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                ->where('document_id', $id)
                ->firstOrFail();

            $Document->update([
                    'url' => $request->document->store('provider/documents'),
                    'status' => 'ASSESSING',
                ]);

            return back();

        } catch (ModelNotFoundException $e) {

            ProviderDocument::create([
                    'url' => $request->document->store('provider/documents'),
                    'provider_id' => \Auth::guard('provider')->user()->id,
                    'document_id' => $id,
                    'status' => 'ASSESSING',
                ]);
            
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	// Api function 
// 	public function updateDocuments(Request $request) {
		
	
// 		$this->validate($request, [
// 			'document' 		=> 'required|mimes:jpg,jpeg,png,pdf',
// 			'type'	   		=> 'required|numeric',
// 			'provider_id'	=> 'required|numeric',
// 		]);
	
		
// 		try{
			
// 				$Document = ProviderDocument::where('provider_id', $request->provider_id )->where('document_id', $request->type )->first();

// 				if( $Document ) {
					
// 					$Document->update([
//                     'url' => $request->document->store('provider/documents'),
//                     'status' => 'ASSESSING',
					
// 					]);
					
// 				} else {
					
// 					$Document = ProviderDocument::create([
// 						'url' => $request->document->store('provider/documents'),
// 						'provider_id' => $request->provider_id,
// 						'document_id' => $request->type,
// 						'status' => 'ASSESSING',
// 					]);
// 				}
				
//                 return response()->json([
// 					'document'=> $Document,
// 					'status'  => 1
//                 ]);
         
		 
//         } catch (Exception $e) {
				
//             return response()->json(['error' => trans('api.something_went_wrong')], 500);
 
//         }
		
// 	}

    public function updateDocuments(Request $request) {
		
	
		$this->validate($request, [
			'document' 		=> 'required|mimes:jpg,jpeg,png,pdf',
			'document_id'	   		=> 'required|numeric',
			'provider_id'	=> 'required|numeric',
			'expires_at'	=> 'required',
			
		]);
	
		
		try{
			
				$Document = ProviderDocument::where('provider_id', $request->provider_id )->where('document_id', $request->document_id )->first();

				if( $Document ) {
					
					$Document->update([
                    'url' => $request->document->store('provider/documents'),
                    'expires_at' => $request->expires_at,
                    'status' => 'ASSESSING',
					
					]);
					
				} else {
					
					$Document = ProviderDocument::create([
						'url' => $request->document->store('provider/documents'),
						'provider_id' => $request->provider_id,
						'document_id' => $request->document_id,
						'expires_at' => $request->expires_at,
						'status' => 'ASSESSING',
					]);
				}
				
                return response()->json([
					'document'=> $Document,
					'status'  => 1
                ]);
         
		 
        } catch (Exception $e) {
				
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
 
        }
		
	}
	
	//Api function
	public function getDocumentTypes( ) {
		
		try {
			   $documents = ProviderDocument::where('provider_id', Auth::user()->id)->get();
                $data = Document::all();
                foreach($data as $k=>$v){
                    $data[$k]['status'] = ProviderDocument::where('provider_id', Auth::user()->id)->where('document_id',$v->id)->value('status')?:'PENDING';  
                }
			
			    return response()->json([
					'document'=> $data,
                ]);
			
		} catch(Exception $e) {
			
			return response()->json(['error' => trans('api.something_went_wrong')], 500);
		}
		
	}

//  public function checkDocument(Request $request) {
		
// 		try {
			
// 			$documents = ProviderDocument::where('provider_id', Auth::user()->id)->count();
                         
//                         if($documents != 0){
			
// 			return response()->json(['status'=> 1,'msg'=>'document found']);

//                         }else{
                        
//                         return response()->json(['status'=> 0,'msg'=>'no document found']);
//                         }
                
				
			
// 		} catch(Exception $e) {
			
// 			return response()->json(['error' => trans('api.something_went_wrong')], 500);
// 		}
		
// 	}


    public function checkDocument(Request $request) {
        
        try {
            
            $documents = ProviderDocument::where('provider_id', Auth::user()->id)->get();
                           $data = Document::all();
                          
            if($request->term_n == 1){
            
                Provider::where('id',Auth::user()->id)->update([
                'term_n' => 1
                ]);
            
            }
            
            if(count($documents) != 0){
                $status = Provider::where('id',Auth::user()->id)->value('term_n'); 
                return response()->json(['status'=> $status,'uniqueId'=>Auth::user()->id.'P'.strtotime(Auth::user()->created_at),'msg'=>'document found','data'=>$data]);

            }else{
                return response()->json(['status'=> 0,'msg'=>'no document found','data'=>$data]);
            }
                
            
        }   catch(Exception $e) {
            
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
        
    }
    
    public function document_status(Request $request)
    {

        $documents = ProviderDocument::where('provider_id', Auth::user()->id)->get();
        foreach($documents as $k=>$v){
          
            $documents[$k]['document_name'] = Document::where('id',$v->document_id)->value('name');
        }
        return $documents;
    
    }




}
