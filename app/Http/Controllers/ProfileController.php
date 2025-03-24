<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function forgotpassword()
    {
        return view('auth.forgot_password', [
            'title' => 'Forgot Password',
        ]);
    }

    public function changePassword(Request $request)
    {
        $id = auth()->user()->id; // Ambil ID pengguna yang sedang login

        // Validasi input untuk kata sandi
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        try {
            // Temukan pengguna berdasarkan ID
            $user = User::findOrFail($id);

            // Periksa apakah kata sandi lama cocok
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
            }

            // Update kata sandi dengan kata sandi baru yang terenkripsi
            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);
            $user->save();

            return redirect()->back()->with('success', 'Kata sandi berhasil diubah.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal mengubah kata sandi: ' . $e->getMessage()]);
        }
    }

    public function storeVA(Request $request)
    {
        // dd($request->all());
        // Validasi input dari form
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'virtual_account_number' => 'required|numeric',
        ]);

        // $request['user_id'] = auth()->user()->id;
        // Membuat Virtual Account baru
        VirtualAccount::create([
            // 'uuid' => uuid_create(),
            'user_id' => auth()->user()->id,
            'nama_bank' => $request->nama_bank,
            'virtual_account_number' => $request->virtual_account_number,
        ]);

        return redirect()->back()->with('success', 'Virtual Account berhasil ditambahkan!');
    }

    public function setting()
    {
        return view('layouts.partials.profil_setting', [
            'title' => 'Profil Setting',
            'virtualAccounts' => auth()->user()->virtualAccount,
        ]);
    }
}
