<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentRequest;
use App\Models\Member;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(view('admin.transaction.index'));
    }

    public function api(Request $request)
    {
        if(auth()->user()->role('admin')){
            if($request->stats){
                if (key_exists('date_start', $request->toArray())) {
                    $model = Transaction::select('*')
                                ->addSelect(DB::raw('(CASE
                                    WHEN status = "0" THEN "Sudah dikembalikan"
                                    ELSE "Belum dikembalikan"
                                    END) AS stats'))
                                ->where('transactions.date_start', '=', $request->date_start)
                                ->orderBy('id', 'desc')
                                ->having('stats' , $request->stats)
                                ->get();
                } else {
                    $model = Transaction::select('*')
                                ->addSelect(DB::raw('(CASE
                                    WHEN status = "0" THEN "Sudah dikembalikan"
                                    ELSE "Belum dikembalikan"
                                    END) AS stats'))
                                ->orderBy('id', 'desc')
                                ->having('stats' , $request->stats)
                                ->get();
                }
            }else {
                if (key_exists('date_start', $request->toArray())) {
                    $model = Transaction::select('*')
                                ->addSelect(DB::raw('(CASE
                                    WHEN status = "0" THEN "Sudah dikembalikan"
                                    ELSE "Belum dikembalikan"
                                    END) AS stats'))
                                ->where('transactions.date_start', '=', $request->date_start)
                                ->orderBy('id', 'desc')
                                ->get();
                } else {
                    $model = Transaction::select('*')
                                ->addSelect(DB::raw('(CASE
                                    WHEN status = "0" THEN "Sudah dikembalikan"
                                    ELSE "Belum dikembalikan"
                                    END) AS stats'))
                                ->orderBy('id', 'desc')
                                ->get();
                }   
            }
            
            return datatables()
            ->of($model)
            ->addColumn('name', function ($model) {
                return $model->members->name;
            })
            ->addColumn('duration', function ($model) {
                $dateStart = Carbon::parse($model->date_start);
                $dateEnd = Carbon::parse($model->date_end);
                return $dateStart->diffInDays($dateEnd);
            })
            ->addColumn('total_book', fn ($model) => $model->transactionDetails->count())
            ->addColumn('format_payment', function ($model) {
                $total = 0; 
                foreach ($model->transactionDetails as $detail) {
                    $harga = $detail->books->price;
                    $qty = $detail->qty;
                    $total += $harga * $qty;
                }
                return convert_number($total);
            })
            ->addColumn('action', function ($model) {
                return '
                    <a href="' . route('transactions.show', $model->id) . '" class="btn btn-primary btn-sm">
                        Detail
                    </a>
                    <a href="' . route('transactions.edit', $model->id) . '" class="btn btn-warning btn-sm">
                        Edit
                    </a>
                    <form action="' . url('/transactions', ['id' => $model->id]) . '" method="post">
                        <input class="btn btn-danger btn-sm" type="submit" value="Delete">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="Delete">
                    </form>';
            })
            ->make();
        } else {
            return abort(403);
        };
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('admin.transaction.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_id' => ['required'], 
            'date_start' => ['required' , 'date_format:Y-m-d'],
            'date_end' => ['required' , 'date_format:Y-m-d' , 'after_or_equal:today'],
            'book_id' => ['required'],
            'status' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'member_id' => $request->input('member_id'),
                'date_start' => $request->input('date_start'),
                'date_end' => $request->input('date_end'),
                'status' => $request->input('status'),
            ]);

            if (count($request->input('book_id')) > 0) {
                foreach ($request->input('book_id') as $id) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'book_id' => $id,
                        'qty' => 1
                    ]);

                    $book = Book::where('id', '=', $id)->first();
                    $book->qty -= 1;
                    $book->save();
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        DB::commit();
        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $members = Member::where('id' , '=' , $transaction->member_id)->select('members.name')->first();

        $listBook = TransactionDetail::join('books' , 'books.id' , '=' , 'transaction_details.book_id')
                                    ->where('transaction_id' , '=' , $transaction->id)
                                    ->select('book_id', 'books.title')
                                    ->get();
        
        return view('admin.transaction.detail' , compact('members' , 'listBook'))->with('transaction' , $transaction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {      
        $books = Book::where('qty', '>', 0)->pluck('title', 'id');

        $members = Member::pluck('name', 'id');

        $details = $transaction->transactionDetails->pluck('book_id')->toArray();
        $no = 1;
        $selected = "[";
        foreach ($details as $value) {
            $selected .= "'" . $value . "'";
            if ($no < count($details))
                 $selected .= ",";
            
            $no++;
        }
        $selected .= "]";

        return view('admin.transaction.edit', compact('transaction', 'books', 'members', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(ParentRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        
        DB::beginTransaction();
        try {
            $transaction->member_id = $data['member_id'];
            $transaction->date_start = $data['date_start'];
            $transaction->date_end = $data['date_end'];
            $transaction->status = $data['status'];
            $transaction->save();


            if($data['status'] == 1){
                //add the qty of book in this transaction before delete this transaction
                $a = TransactionDetail::where('transaction_id' , '=' , $transaction->id)->pluck('book_id');
                foreach($a as $val){
                    $b = Book::where('id' , '=' , $val)->first();
                    $b->qty += 1;
                    $b->save();
                }
                // delete previous books
                TransactionDetail::where('transaction_id', '=', $transaction->id)->delete();

                if (count($data['book_id']) > 0){
                    foreach ($data['book_id'] as $id) {
                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'book_id' => $id,
                            'qty' => 1
                        ]);
                        $book = Book::where('id', '=', $id)->first();
                        $book->qty -= 1;
                        $book->save();
                    }
                }
            } else {
                if (count($data['book_id']) > 0){
                    foreach ($data['book_id'] as $id) {
                        $book = Book::where('id', '=', $id)->first();
                        $book->qty += 1;
                        $book->save();
                    }
                }
            }

        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->route('transactions.edit', $transaction->id)->with('error', $th->getMessage());
        }
        DB::commit();

        return redirect()->route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $a = TransactionDetail::where('transaction_id' , '=' , $transaction->id)->pluck('book_id');
                foreach($a as $val){
                    $b = Book::where('id' , '=' , $val)->first();
                    $b->qty += 1;
                    $b->save();
                }
        $transDet = TransactionDetail::join('transactions' , 'transactions.id' , '=' , 'transaction_details.transaction_id')
                                            ->where('transaction_details.transaction_id' , '=' , $transaction->id)
                                            ->delete();
        
        $transaction->delete();

        return redirect()->back();
    }

    public function selectmbr(Transaction $transaction)
    {
        $transaction = Member::where('name', 'LIKE', '%' . request('q') . '%')->paginate(100);

        return response()->json($transaction);
    }

    public function selectbk()
    {
        $books = Book::where('title', 'LIKE', '%' . request('q') . '%')
                                ->where('qty' , '>' , 0)
                                ->paginate(100);

        return response()->json($books);
    }
}
