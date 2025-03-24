<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Kode OTP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
  <div class="max-w-md mx-auto mt-10">
    <!-- Header -->
    <header class="flex items-center justify-between border-b pb-4">
      <button onclick="history.back()" class="text-black">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h1 class="text-2xl font-bold text-black">Verifikasi Kode OTP</h1>
      <div></div>
    </header>

    <!-- Content -->
    <div class="mt-6 text-center">
      <p class="text-gray-500 text-sm">Pilih salah satu cara di bawah ini untuk mendapatkan kode OTP</p>
      <img src="assets/images/otp_illustration.png" alt="OTP Illustration" class="mx-auto mt-6 w-36 h-36">
    </div>

    <!-- Options -->
    <div class="mt-6 space-y-4">
      <!-- Email Option -->
      <button class="w-full flex items-center p-4 border rounded-lg shadow-sm hover:bg-gray-100">
        <img src="assets/images/email.png" alt="Email" class="w-8 h-8">
        <div class="ml-4 text-left">
          <p class="font-bold">Kirim lewat Email</p>
          <p class="text-gray-500 text-sm">email@example.com</p>
        </div>
      </button>

      <!-- WhatsApp Option -->
      <button class="w-full flex items-center p-4 border rounded-lg shadow-sm hover:bg-gray-100">
        <img src="assets/images/whatsapp.png" alt="WhatsApp" class="w-8 h-8">
        <div class="ml-4 text-left">
          <p class="font-bold">Kirim lewat WhatsApp</p>
          <p class="text-gray-500 text-sm">+62 812-3456-7890</p>
        </div>
      </button>

      <!-- SMS Option -->
      <button class="w-full flex items-center p-4 border rounded-lg shadow-sm hover:bg-gray-100">
        <img src="assets/images/sms.png" alt="SMS" class="w-8 h-8">
        <div class="ml-4 text-left">
          <p class="font-bold">Kirim lewat SMS</p>
          <p class="text-gray-500 text-sm">+62 812-3456-7890</p>
        </div>
      </button>
    </div>
  </div>

  <!-- Script for interactivity -->
  <script>
    // Example: Add event listeners for buttons (replace with real functionality)
    document.querySelectorAll('button').forEach(button => {
      button.addEventListener('click', () => {
        alert('Mengirim OTP via ' + button.querySelector('p.font-bold').textContent);
      });
    });
  </script>
</body>
</html>
