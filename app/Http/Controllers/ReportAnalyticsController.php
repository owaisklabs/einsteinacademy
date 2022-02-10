<?php

namespace App\Http\Controllers;

use App\Models\ReportAnalytics;
use Illuminate\Http\Request;

class ReportAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.report analytics.report_analytics');
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
     * @param  \App\Models\ReportAnalytics  $reportAnalytics
     * @return \Illuminate\Http\Response
     */
    public function show(ReportAnalytics $reportAnalytics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportAnalytics  $reportAnalytics
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportAnalytics $reportAnalytics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportAnalytics  $reportAnalytics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportAnalytics $reportAnalytics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportAnalytics  $reportAnalytics
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportAnalytics $reportAnalytics)
    {
        //
    }
}
