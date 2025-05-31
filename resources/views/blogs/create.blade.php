<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mt-10 mb-6">Create Blog</h1>
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" class="input w-full" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Content</label>
                <textarea name="content" class="input w-full" rows="6" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="input w-full">
            </div>
            <button type="submit" class="bg-[#523D35] text-white font-bold py-2 px-6 rounded hover:bg-[#886658]">Create Blog</button>
        </form>
    </div>
</x-app-layout>