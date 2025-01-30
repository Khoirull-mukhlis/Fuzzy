<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">
            Masukkan Data Persediaan dan Permintaan
        </h1>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form -->
        <form action="/process" method="POST" id="inputForm" class="space-y-4">
            @csrf
            <div>
                <label for="persediaan" class="block text-sm font-medium text-gray-700">
                    Persediaan (30 - 45):
                </label>
                <input type="number" name="persediaan" id="persediaan" min="30" max="45" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @if ($errors->has('persediaan'))
                <p class="text-red-500 text-sm">{{ $errors->first('persediaan') }}</p>
                @endif
            </div>

            <div>
                <label for="permintaan" class="block text-sm font-medium text-gray-700">
                    Permintaan (10 - 40):
                </label>
                <input type="number" name="permintaan" id="permintaan" min="10" max="40" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @if ($errors->has('permintaan'))
                <p class="text-red-500 text-sm">{{ $errors->first('permintaan') }}</p>
                @endif
            </div>

            <div class="text-center">
                <button type="submit"
                    class="w-full bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    Proses
                </button>
            </div>
        </form>
    </div>
</body>

</html>
