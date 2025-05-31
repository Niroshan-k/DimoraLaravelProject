<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Edit Blog</h1>
        <form action="{{ route('blogs.update', $blog->_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" class="input w-full" value="{{ old('title', $blog->title) }}" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Content</label>
                <textarea name="content" class="input w-full" rows="6" required>{{ old('content', $blog->content) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="input w-full">
                <div class="flex gap-2 mt-2 flex-wrap">
                    @foreach($blog->images ?? [] as $img)
                        <img src="{{ asset('storage/' . $img) }}" class="w-20 h-20 object-cover rounded" alt="">
                    @endforeach
                </div>
            </div>
            <button type="submit" class="bg-[#523D35] text-white font-bold py-2 px-6 rounded hover:bg-[#886658]">Update Blog</button>
        </form>
    </div>
</x-app-layout>