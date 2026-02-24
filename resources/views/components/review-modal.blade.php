@props(['orderItem', 'orderId'])

<div x-data="reviewForm({{ $orderItem->id }}, {{ $orderId }})" x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div @click.away="open = false" class="relative bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-8">
            <button @click="open = false" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800">&times;</button>
            <h3 class="text-2xl font-bold mb-4">Review: {{ $orderItem->product->name }}</h3>

            <form :action="formAction" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" :value="productId">
                <input type="hidden" name="order_id" :value="orderId">
                
                <div class="mb-4">
                    <label class="block text-slate-700 mb-2">Your Rating</label>
                    <div class="flex items-center space-x-1" @mouseleave="hoverRating = 0">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <svg @mouseenter="hoverRating = star" @click="rating = star" class="w-8 h-8 cursor-pointer"
                                :class="(hoverRating >= star || rating >= star) ? 'text-amber-400' : 'text-slate-300'"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z" />
                            </svg>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="rating">
                </div>

                <div class="mb-4">
                    <label for="comment" class="block text-slate-700 mb-2">Your Comment (Optional)</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full input"></textarea>
                </div>

                <div class="mb-6">
                    <label for="photo" class="block text-slate-700 mb-2">Upload Photo (Optional)</label>
                    <input type="file" name="photo" id="photo" class="w-full text-sm text-slate-500 file:btn-secondary file:mr-4">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary" :disabled="rating === 0">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function reviewForm(productId, orderId) {
        return {
            open: false,
            rating: 0,
            hoverRating: 0,
            productId: productId,
            orderId: orderId,
            formAction: '{{ route("reviews.store") }}',
        }
    }
</script>
<style>
    .input { border: 1px solid #cbd5e1; padding: 0.5rem 0.75rem; border-radius: 0.375rem; width: 100%; }
    .btn-primary { background-color: #0ea5e9; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; }
    .btn-secondary { background-color: #f1f5f9; color: #475569; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; }
    .btn-primary:disabled { background-color: #94a3b8; cursor: not-allowed; }
</style>
