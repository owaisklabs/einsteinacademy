<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\PastPaper;
use App\Models\PastPaperMedia;
use App\Models\PastPaperRating;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PastPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $past_papers = PastPaper::all();
        return view('admin.past paper.past_paper',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grades= Grade::all();
        $subjects= Subject::all();
        return view('admin.past paper.create_past_paper',compact('grades','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subject' => 'required |numeric',
            'grade' => 'required |numeric',
            'files.*' => 'required|mimes:pdf',
        ]);
        // return $request->all();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $pastPaper = new PastPaper();
        $pastPaper->title  = $request->title;
        $pastPaper->grade_id  = $request->grade;
        $pastPaper->subject_id  = $request->subject;
        $pastPaper->save();
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = preg_replace('/\..+$/', '', $file->getClientOriginalName());
                $attachSatResultName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                Storage::disk('public_past_papers')->put($attachSatResultName, \File::get($file));

                $media = new PastPaperMedia();
                $media->name = $fileName;
                $media->past_paper_id = $pastPaper->id;
                $media->path = url('media/past_papers/'.$attachSatResultName);
                $media->save();
            }
            $request->session()->flash('past-paper-add');
            return redirect()->route('past-paper.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PastPaper  $pastPaper
     * @return \Illuminate\Http\Response
     */
    public function show(PastPaper $pastPaper)
    {
        $past_paper= $pastPaper->load('Medias');
        return view('admin.past paper.show_past_paper',get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PastPaper  $pastPaper
     * @return \Illuminate\Http\Response
     */
    public function edit(PastPaper $pastPaper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PastPaper  $pastPaper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PastPaper $pastPaper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PastPaper  $pastPaper
     * @return \Illuminate\Http\Response
     */
    public function destroy(PastPaper $pastPaper)
    {
        //
    }
    public function indexApi()
    {
        $pastPaper = PastPaper::all();
        return $this->formatResponse('success', 'get all past papers', $pastPaper);
    }
    public function showApi($id)
    {
        $pastPaper = PastPaper::where('id',$id)->with('grade','subject','medias')->get();
        $pastPaper['rating'] = PastPaperRating::where('study_material_id',$id)->avg('rating');
        if( $pastPaper['rating'] == NULL)
        $pastPaper['rating'] =0;
        return $this->formatResponse('sucess','get past paper',$pastPaper);
    }
}
