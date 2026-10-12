namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Menampilkan halaman Ulasan Saya dengan data asli database
    public function index()
    {
        $user = Auth::user();
        $reviews = Review::where('user_id', $user->id)->with('order.items.menu')->latest()->paginate(5);
        
        // Hitung statistik ulasan
        $totalReviews = Review::where('user_id', $user->id)->count();
        $averageRating = $totalReviews > 0 ? Review::where('user_id', $user->id)->avg('rating') : 0;
        
        // Hitung distribusi bintang (5, 4, 3, 2, 1)
        $starCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $starCounts[$i] = Review::where('user_id', $user->id)->where('rating', $i)->count();
        }

        return view('customer.reviews', compact('reviews', 'totalReviews', 'averageRating', 'starCounts'));
    }

    // Menampilkan form beri ulasan untuk pesanan tertentu
    public function create(Order $order)
    {
        // Pastikan pesanan milik user yang sedang login dan statusnya sudah selesai
        if ($order->user_id !== Auth::id() || $order->status !== 'Selesai') {
            abort(403);
        }

        return view('customer.create-review', compact('order'));
    }

    // Menyimpan ulasan ke database
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('customer.reviews')->with('success', 'Ulasan berhasil dikirim!');
    }
}