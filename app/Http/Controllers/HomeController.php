<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Catalog;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $members = Member::with('user')->get();
        // $books = Book::with('publisher')->get();
        // $books = Book::with('author')->get();
        // $books = Book::with('catalog')->get();
        // $publisher = Publisher::with('books')->get();
        // $author = Author::with('books')->get();
        // $catalog = Catalog::with('books')->get();

        // no 1
        $data = Member::select('*')
            ->join('users', 'users.member_id' , '=' , 'members.id')
            ->get();
        
        // no 2
        $data2 = Member::select('*')
            ->LeftJoin('users', 'users.member_id' , '=' , 'members.id')
            ->where('users.id', NULL)
            ->get();
        
        // no 3
        $data3 = Transaction::select('members.id', 'members.name')
            ->RightJoin('members', 'members.id' , '=' , 'transactions.member_id')
            ->where('transactions.member_id', NULL)
            ->get();
            
        // no 4
        $data4 = Member::select('members.id', 'members.name', 'members.phone_number')
            ->join('transactions', 'transactions.member_id' , '=' , 'members.id')
            ->orderBy('members.id', 'asc')
            ->get();

        // no 5
        $data5 = Member::select('members.id' , 'members.name' , 'members.phone_number' , 'transactions.id' , DB::raw('count("") as frequency_transaction'))
            ->join('transactions', 'transactions.member_id' , '=' , 'members.id')
            ->groupBy('members.id')
            ->havingRaw('count("") >  1')
            ->get();

        // no 6
        $data6 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->get();

        // no 7
        $data7 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->whereMonth('transactions.date_end', '06')
            ->WhereYear('transactions.date_end' , '2021')
            ->get();

        // no 8
         $data8 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->whereMonth('transactions.date_start', '05')
            ->WhereYear('transactions.date_start' , '2021')
            ->get();

        // no 9
        $data9 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->whereMonth('transactions.date_start', '06')
            ->WhereYear('transactions.date_start' , '2021')
            ->whereMonth('transactions.date_end', '06')
            ->WhereYear('transactions.date_end' , '2021')
            ->get();

        // no 10
        $data10 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->where('members.address', 'like' , 'bandung%')
            ->get();

        // no 11
        $data11 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->where('members.address', 'like' , 'bandung%')
            ->where('members.gender' , '=' , 'M')
            ->get();

        // no 12
        $data12 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end' , 'transaction_details.book_id' , 'transaction_details.qty')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->join('transaction_details' , 'transaction_details.transaction_id' , '=' , 'transactions.id')
            ->where('transaction_details.qty', '>' , '1')
            ->get();

        // no 13
        $data13 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end' , 'transaction_details.book_id' , 'transaction_details.qty' , 'books.title' , 'books.price', DB::raw('transaction_details.qty*books.price as total_price'))
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->join('transaction_details' , 'transaction_details.transaction_id' , '=' , 'transactions.id')
            ->join('books' , 'transaction_details.book_id' , '=' , 'books.id')
            ->get();

        // no 14
        $data14 = Member::select('members.name' , 'members.phone_number' , 'members.address' , 'transactions.date_start' , 'transactions.date_end' , 'transaction_details.book_id' , 'transaction_details.qty' , 'books.title' , 'publishers.name', 'authors.name' , 'catalogs.name')
            ->join('transactions' , 'transactions.member_id' , '=' , 'members.id')
            ->join('transaction_details' , 'transaction_details.transaction_id' , '=' , 'transactions.id')
            ->join('books' , 'transaction_details.book_id' , '=' , 'books.id')
            ->join('publishers' , 'publishers.id' , '=' , 'books.publisher_id')
            ->join('authors' , 'authors.id' , '=' , 'books.author_id')
            ->join('catalogs', 'catalogs.id' , '=' , 'books.catalog_id')
            ->get();

        // no 15
        $data15 = Catalog::select('catalogs.id' , 'catalogs.name' , 'books.title')
            ->leftJoin('books' , 'catalogs.id' , '=' , 'books.catalog_id')
            ->get();

        // no 16
        $data16 = Book::select('books.id' , 'books.year' , 'books.publisher_id' , 'books.author_id' , 'books.catalog_id' , 'books.qty' , 'books.price' , 'publishers.name')
              ->leftJoin('publishers' , 'books.publisher_id' , '=' , 'publishers.id')
            ->get();

        // no 17
        $data17 = Book::selectRaw('count("") as numbers_of_author')
            ->where('author_id' , '5')
            ->get();

        // no 18
        $data18 = Book::select('*')
            ->where('price' , '>' , '10000')
            ->get();

        // no 19
        $data19 = Book::select('*')
            ->where('publisher_id' , '=' , '1')
            ->where('qty' , '>' , '10')
            ->get();

        // no 20
        $data20 = Member::select('*')
            ->whereMonth('created_at', '06')
            ->whereYear('created_at' , '2021')
            ->get();

        // return $data5;
        return view('home');
    }
    
    public function home()
    {
        $numbers_of_member = Member::count();
        $numbers_of_book = Book::count();
        $numbers_of_transaction = Transaction::whereMonth('date_start' , date('m'))->count();
        $numbers_of_publisher = Publisher::count();

        $data_donut = Book::select(DB::raw("COUNT(publisher_id) as total" ))->groupBy('publisher_id')->orderBy('publisher_id' , 'asc')->pluck('total');
        $label_donut = Publisher::orderBy('publishers.id' , 'asc')->join('books' , 'books.publisher_id' , '=' , 'publishers.id')->groupBy('publishers.name')->pluck('publishers.name');

        $label_bar = ['Transaction Start' , 'Transaction End'];
        $data_bar = [];
        
        foreach($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] = $key == 0 ?  'rgba(60,140,188,0.9)' : 'rgba(210,214,222, 1)';
            $data_month = [];

            foreach(range(1,12) as $month) {
                if($key == 0) {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_start' , $month)->first()->total;
                } else {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_end' , $month)->first()->total;
                }
            }

            $data_bar[$key]['data'] = $data_month;
        }
        
        $label_bar2 = ['Book Price'];
        $book_price = Book::select('price')->pluck('price');
        $data_bar2 = [];
        foreach($label_bar2 as $key => $value){
            $data_bar2[$key]['label'] = $label_bar2[$key];
            $data_bar2[$key]['backgroundColor'] = 'rgba(60,140,188,0.9)';
            $data_bar2[$key]['data'] = $book_price;
        }

        $book_id = Book::select('id')->orderBy('id' , 'asc')->pluck('id');

        return response(view('home' , compact('numbers_of_book' , 'numbers_of_member' , 'numbers_of_transaction' , 'numbers_of_publisher' , 'data_donut' , 'label_donut' , 'data_bar' , 'data_bar2' , 'book_id')));
    }

    public function test_spatie()
    {
        // $role = Role::create(['name' => 'admin']);
        // $permission = Permission::create(['name' => 'transaction index']);

        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);

        $user = auth()->user();
        $user->assignRole('admin');
    }

}
