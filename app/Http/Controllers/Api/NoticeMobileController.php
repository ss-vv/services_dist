<?php

namespace App\Http\Controllers\Api;

use App\Models\NoticeMobile;
use Illuminate\Http\Request;

class NoticeMobileController extends ApiController
{
    protected $noticeMobile;

    public function __construct(NoticeMobile $noticeMobile)
    {
        parent::__construct();
        $this->noticeMobile = $noticeMobile;
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validateRequest($request,[
            'mobile'=>'required|string|size:11',
            'status'=>'required|in:0,1'
            ]);
        $this->noticeMobile->fill([
            'mobile'      => $request->input('mobile'),
            'status'          =>  $request->input('status'),
        ])->save();
    }

    public function update(Request $request)
    {
        $id = request('id');
        $this->validateRequest($request,[
            'mobile'=>'required|string|size:11',
            'status'=>'required|in:0,1'
        ]);
        if($id!==null){
            $this->noticeMobile->where('id', $id)->update([
                'mobile'      => $request->input('mobile'),
                'status'          =>  $request->input('status')
            ]);
            return $this->apiResponse->noContent();
        }
        $this->apiResponse->error('参数有误');
    }


    public function destroy()
    {
        $id = request('id');
        if($id!==null){
            $this->noticeMobile->where('id', $id)->update(['status'=>0]);
            return $this->apiResponse->noContent();
        }
        $this->apiResponse->error('参数有误');
    }
}
