<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ImageUploaded;
use Image;
use File;
use Carbon\Carbon; // untuk mendefinisikan tanggal atau waktu

class ProductController extends Controller
{
    public $path;
    public $fullPath;
    public $dimensions;

    public function __construct(Product $product, ImageUploaded $imageUploaded) {
        $this->product       = $product;
        $this->imageUploaded = $imageUploaded;

        $this->path         = 'assets/images/gallery';
        $this->fullPath     = storage_path('app/public/'.$this->path);
        $this->dimensions   = ['200x200', '600x600', '1080x1440'];
    }

    public function store(Request $request) {
        $data = $request->all(); // untuk mendapatkan semua body yang dikirim di API
    
        $validators = Validator::make($data, [
            'title'       => 'required', 
            'description' => 'required|min:5', 
            'price'       => 'required',
            'rating'      => 'required',
            'image.*'     => 'required|image|mimes:jpg,png,jpeg'
        ]);
        
        if ($validators->fails()) {
            return response()->json([
                'success'  => false,
                'data'    => [],
                'message' => $validators->errors()->all()
            ], 400);
        }

        if (count($data['image']) > 4) {
            return response()->json([
                'success'  => false,
                'data'    => [],
                'message' => 'Uploadeds images must not be more than 4'
            ], 400);
        }

        // untuk custom string pada field title
        $data['slug'] = Str::slug($data['title'], '-');
        // untuk menyimpan data ke database
        $store = $this->product->create($data);

        if ($store) {
            
            // jika folder belum ada
            if(!File::isDirectory($this->fullPath)) {
                // maka folder tersebut akan dibuat
                File::makeDirectory($this->fullPath, 0777, true);
            }

            $files = $request->file('image');

            // untuk mengambil file image
            foreach ($files as $file) {
                // membuat nama file dari gabungan timestamp dan uniqid()
                $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // upload original file (belum diubah dimensinya)
                Image::make($file)->save($this->fullPath . '/' . $fileName);
                
                // looping array dimensi yang di inginkan (yang telah di definsikan pada constructor)
                foreach ($this->dimensions as $dimension) {
                    // memecah dimensions
                    $separateDimension = explode("x", $dimension);
                    
                    // membuat canvas image sebesar dimensi yang ada di dalam array
                    $canvas = Image::canvas($separateDimension[0], $separateDimension[1]);

                    // resize image sesuai yang ada di dalam array dengan memperhatikan ratio
                    $resizeImage = Image::make($file)->resize($separateDimension[0], $separateDimension[1], function($constraint) {
                        $constraint->aspectRatio();
                    });

                    // cek jika foldernya belum ada
                    if (!File::isDirectory($this->fullPath . '/' . $dimension)) {
                        // maka membuat folder untuk dimensi masing"
                        File::makeDirectory($this->fullPath . '/' . $dimension, 0777, true);
                    }

                    // memasukan image yang telah di resize ke dalam canvas
                    $canvas->insert($resizeImage, 'center');
                    // simpan image ke dalam masing" folder (dimensi)
                    $canvas->save($this->fullPath . '/' . $dimension . '/' . $fileName);
                }

                // simpan data image yang telah di upload
                $this->imageUploaded->create([
                    'product_id' => $store->id,
                    'name'       => $fileName,
                    'dimensions' => implode('|', $this->dimensions),
                    'path'       => 'storage/'.$this->path,
                    'extension'  => $file->getClientOriginalExtension()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data'    => $store->with('imageUploaded')->orderBy('id', 'DESC')->first(),
                'message' => 'Data product successfully stored'
            ], 200);
        }
        return response()->json([
            'success' => false,
            'data'    => [],
            'message' => 'Something went wrong'
        ], 400);
    }
}
