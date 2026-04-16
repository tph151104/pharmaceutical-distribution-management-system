<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureToggle;

class FeatureToggleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = FeatureToggle::all();
        return view('admin.features.index', compact('features'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $feature = FeatureToggle::findOrFail($id);
        
        $request->validate([
            'trang_thai' => 'required|boolean',
        ]);

        $feature->trang_thai = $request->trang_thai;
        $feature->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        }

        return redirect()->route('admin.features.index')->with('success', 'Đã cập nhật trạng thái chức năng');
    }
}
