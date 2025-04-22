<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Librarys;
use App\models\MessContact;
use App\Services\CloudflareImageService;

class AllController extends Controller
{

    protected $cloudflareService;

    public function __construct(CloudflareImageService $cloudflareService)
    {
        $this->cloudflareService = $cloudflareService;
    }

    public function uploadImage(Request $request)
    {
        if($imgAvatar = $request->file('img')){
            // $nameAvatar = $imgAvatar->getClientOriginalName();
            // $fintimg = strpos($nameAvatar, '.jpg');
            // if($fintimg == true){
            //     $nameImg = $request->title_post.'-'.rand().'.jpg';
            // }else{
            //     $nameImg = $request->title_post.'-'.rand().'.png';
            // }
            // $imgAvatar->move('uploads/images/', $nameImg);
            if ($request->oldImages) {
                foreach ($request->oldImages as $key => $image) {
                    // Kiểm tra xem có phải đường dẫn Cloudflare Image hay không
                    if (filter_var($image, FILTER_VALIDATE_URL) &&
                        preg_match('/^https:\/\/imagedelivery\.net\/[A-Za-z0-9_-]+\/[A-Za-z0-9-]+\/(public|private)$/', $image)) {
                        $this->cloudflareService->deleteImage($image);
                    } else {
                        $url = $image;
                        $path = parse_url($url, PHP_URL_PATH);
                        $path = public_path($path);
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                }
            }
            $response = $this->cloudflareService->uploadImage($imgAvatar);
            return response()->json([
                'messenge' => 'success',
                'path' => $response['result']['variants'][0],
            ],200);
        }else{
            return response()->json([
                'data' => 'fail'
            ],500);
        }
    }

    public function deleteImage(Request $request)
    {
        if ($request->image) {
            $image = $request->image;
            // Kiểm tra xem có phải đường dẫn Cloudflare Image hay không
            if (filter_var($image, FILTER_VALIDATE_URL) &&
                preg_match('/^https:\/\/imagedelivery\.net\/[A-Za-z0-9_-]+\/[A-Za-z0-9-]+\/(public|private)$/', $image)) {
                $this->cloudflareService->deleteImage($image);
            } else {
                $url = $image;
                $path = parse_url($url, PHP_URL_PATH);
                $path = public_path($path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            return response()->json([
                'messenge' => 'success',
            ],200);
        } else {
            return response()->json([
                'data' => 'fail'
            ],500);
        }
    }
    public function uploadImageMulti(Request $request)
    {
        $uploadId = [];
        if($files = $request->file('file')){
            foreach($request->file('file') as $key => $file){
                // $name = rand().$file->getClientOriginalName();
                // $fielname = $file->move('uploads/imagesMuli/', $name);
                // $uploadId[] = url('/').'/uploads/images/'.$name;
                $response = $this->cloudflareService->uploadImage($file);
                $uploadId[] = $response['result']['variants'][0];
            }
        }
        return response()->json([
            'messenge' => 'success',
            'path' => $uploadId
        ],200);
    }
    public function fileStore(Request $request)
    {
        $upload_path = public_path('upload');
        $file_name = $request->file->getClientOriginalName();
        $generated_new_name = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move($upload_path, $generated_new_name);

        $insert['title'] = $generated_new_name;
        return response()->json([
            'messenge' => 'success',
            'path' => $insert
        ],200);
    }
    public function listMesscontact(Request $request)
    {
        $keyword = $request->keyword;
        if($keyword == ""){
            $data = MessContact::orderBy('id','DESC')->get();
        }else{
            $data = MessContact::where('title', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->get()->toArray();
        }
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
}
