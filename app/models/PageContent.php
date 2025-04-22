<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\language\Language;

class PageContent extends Model
{
    protected $table = "page_contents";
    public function rule()
    {

    }
    public function savePageContent($request)
    {
        $quiz_id = $request->quiz_id;
        $language = $request->language;
        $nextId = PageContent::max('id') + 1;
        if($quiz_id != "" && $language != ""){
            $query = PageContent::where([
                'quiz_id' => $quiz_id,
                'language'=> $language
             ])->first();
            if ($query) {
                $query->title = json_encode($request->title);
                $query->content = json_encode($request->content);
                $query->status = $request->status;
                $query->type = $request->type;
                $query->slug = to_slug($request->title[0]['content']);
                $query->image = $request->image;
                $query->description = json_encode($request->description);
                $query->save();
            }else{
                $query = new PageContent();
                $query->quiz_id = $quiz_id;
                $query->language = $language;
                $query->title = json_encode($request->title);
                $query->content = json_encode($request->content);
                $query->status = $request->status;
                $query->type = $request->type;
                $query->image = $request->image;
                $query->slug = to_slug($request->title[0]['content']);
                $query->description = json_encode($request->description);
                $query->save();
            }

        }else{
            // $listLanguage = Language::get()->toArray();
            // foreach ($listLanguage as $item) {
                $query = new PageContent();
                $query->quiz_id = $nextId;
                $query->language = 'vi';
                $query->title = json_encode($request->title);
                $query->content = json_encode($request->content);
                $query->status = $request->status;
                $query->type = $request->type;
                $query->image = $request->image;
                $query->slug = to_slug($request->title[0]['content']);
                $query->description = json_encode($request->description);
                $query->save();
            // }
        }
        return $query;

    }
}
