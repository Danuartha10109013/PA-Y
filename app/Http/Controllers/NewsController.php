<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // Menampilkan semua berita
    public function news()
    {
        $newsItems = News::all(); // Change this line to use $newsItems
        return view('pages.admin.kelola_news', compact('newsItems')); // Ensure to pass the correct variable
    }

    // Menyimpan berita baru ke database
    // public function create(Request $request)
    // {
    //     // $request->validate([
    //     'title' => 'required|string|max:255',
    //     'deskripsi' => 'required|string',
    //     'upload_date' => 'required|date',
    //     'uploaded_by' => 'required|string|max:255',
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust as necessary
    // ]);

    // // Save data to the database
    // $news = new News; // Assuming you have a News model
    // $news->title = $request->title;
    // $news->deskripsi = $request->deskripsi;
    // $news->upload_date = $request->upload_date;
    // $news->uploaded_by = $request->uploaded_by;

    // // Handle file upload
    // if ($request->hasFile('image')) {
    //     $image = $request->file('image');
    //     $imageName = time() . '.' . $image->getClientOriginalExtension();
    //     $image->move(public_path('images'), $imageName);
    //     $news->image = $imageName;
    // }

    // $news->save();

    // return redirect()->route('your.redirect.route')->with('success', 'Berita berhasil ditambahkan!');

    public function create(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'upload_date' => 'required|date',
            'uploaded_by' => 'required|string|max:255',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,doc,docx,pdf,txt,xls,xlsx,mp4,mkv,avi,mov|max:20480',

        ]);

        // Buat entri berita baru
        $news = News::create([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'upload_date' => $request->upload_date,
            'uploaded_by' => $request->uploaded_by,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/files'), $fileName);

            // Store the file names in the database
            $news->nama_berkas = $fileName;
            $news->original_name = $file->getClientOriginalName();
            $news->save();
        }


        return redirect()->back()->with('success', 'Berita berhasil ditambahkan!');
    }


    // Menampilkan form untuk mengedit berita
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news); // Return JSON data for the edit modal
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'upload_date' => 'required|date',
            'uploaded_by' => 'required|string|max:255',
        ]);

        $news = News::findOrFail($id);

        // Check if a new file is uploaded
        if ($request->hasFile('file')) {
            if ($news->nama_berkas) {
                $oldFilePath = public_path($news->nama_berkas);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Save the new file
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file->move(public_path('uploads'), $fileName);
            $news->nama_berkas = 'uploads/' . $fileName;
        }

        // Update the news in the database
        $news->update([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'upload_date' => $request->upload_date,
            'uploaded_by' => $request->uploaded_by,
        ]);

        return redirect()->back()->with('success', 'Berita berhasil diedit!');
    }


    public function delete($id)
    {
        $anggota = News::findOrFail($id);
        $anggota->delete();

        return redirect()->back()->with('success', 'Data anggota berhasil dihapus!');
    }
}
