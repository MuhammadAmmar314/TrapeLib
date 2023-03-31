<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentRequest;
use App\Models\Book;
use App\Models\Child;
use App\Models\Member;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = Parents::orderBy('id', 'desc')->get();
            return datatables()->of($model)
                ->addColumn('name', fn ($m) => $m->member->name )
                ->addColumn('action', function ($m) {
                    return view('admin.parent.action', compact('m'));
                })
                ->addColumn('total_book', function ($m) {
                    return $m->childs->count();
                })
                ->make();
        }

        return view('admin.parent.index');
    }

    public function edit(Parents $parent)
    {
        $books = Book::where('qty', '>', 0)->pluck('title', 'id');

        $members = Member::pluck('name', 'id');

        $details = $parent->childs->pluck('book_id')->toArray();
        $no = 1;
        $selected = "[";
        foreach ($details as $value) {
            $selected .= "'" . $value . "'";
            if ($no < count($details))
                 $selected .= ",";
            
            $no++;
        }
        $selected .= "]";

        return view('admin.parent.edit', compact('parent', 'books', 'members', 'selected'));
    }

    public function update(Parents $parent, ParentRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $parent->member_id = $data['member_id'];
            $parent->save();

            // delete previous child
            Child::where('parents_id', '=', $parent->id)->delete();

            // insert new child
            foreach ($data['book_id'] as $bookId) {
                Child::create([
                    'book_id' => $bookId,
                    'parents_id' => $parent->id
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->route('parent.index')->with('error', $th->getMessage());
        }
        DB::commit();

        return redirect()->route('parent.index');
    }
}
