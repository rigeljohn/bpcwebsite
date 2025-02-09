<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    public function deleteNews(News $news)
    {
        $news->delete();
        toastr()->success('', 'News deleted successfully!', [
            "showEasing" => "swing",
            "hideEasing" => "swing",
            "showMethod" => "slideDown",
            "hideMethod" => "slideUp"
        ]);
        return back();
    }

    public function updateNews(News $news, Request $request)
    {
        // if ($news->title !== $request->input('title') || $news->author !== $request->input('author') || $news->description !== $request->input('description') || $news->thumbnail !== $request->input('thumbnail') || $news->link !== $request->input('link')) {

        $incomingFields = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'thumbnail' => 'nullable|image|max:5000',
            'link' => ['nullable', 'string', 'url'],
        ]);

        if ($news->title !== $incomingFields['title'] || $news->author !== $incomingFields['author'] || $news->description !== $incomingFields['description'] || $request->hasFile('thumbnail') || $news->link !== $incomingFields['link']) {
            // Update the existing fields
            $news->title = trim(strip_tags(ucwords($incomingFields['title'])));
            $news->author = trim(strip_tags(ucwords($incomingFields['author'])));
            $news->description = $incomingFields['description'];
            $news->link = $incomingFields['link'];
            $news->updated_by = auth()->user()->username;

            // Handle the thumbnail if provided
            if ($request->hasFile('thumbnail')) {
                $thumbnail_name = $incomingFields['title'] . uniqid() . '.jpg';
                $imgData = Image::make($request->file('thumbnail'))->encode('jpg');
                Storage::put('public/news-thumbnail/' . $thumbnail_name, $imgData);
                $news->thumbnail = $thumbnail_name;
            }

            // Save the updated News instance
            $news->save();

            toastr()->success('', 'News updated successfully!', [
                "showEasing" => "swing",
                "hideEasing" => "swing",
                "showMethod" => "slideDown",
                "hideMethod" => "slideUp"
            ]);

            return back();
        } else {
            toastr()->warning('', 'No changes has been saved!', [
                "showEasing" => "swing",
                "hideEasing" => "swing",
                "showMethod" => "slideDown",
                "hideMethod" => "slideUp"
            ]);
            return back();
        }
    }


    public function addNews(Request $request)
    {

        $incomingFields = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'thumbnail' => 'nullable|image|max:5000',
            'link' => ['nullable', 'string', 'url'],
        ]);


        if ($request->hasFile('thumbnail')) {
            $thumbnail_name = $incomingFields['title'] . uniqid() . '.jpg';
            $imgData = Image::make($request->file('thumbnail'))->encode('jpg');
            Storage::put('public/news-thumbnail/' . $thumbnail_name, $imgData);
            $incomingFields['thumbnail'] = $thumbnail_name;
        }

        $incomingFields['title'] = trim(strip_tags(ucwords($incomingFields['title'])));
        $incomingFields['author'] = trim(strip_tags(ucwords($incomingFields['author'])));

        $incomingFields['posted_by'] = auth()->user()->username;
        $incomingFields['updated_by'] = auth()->user()->username;

        // dd($incomingFields);
        News::create($incomingFields);


        toastr()->success('', 'News added successfully!', [
            "showEasing" => "swing",
            "hideEasing" => "swing",
            "showMethod" => "slideDown",
            "hideMethod" => "slideUp"
        ]);
        return back();

    }
}