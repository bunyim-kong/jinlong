<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Jinlong Property</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
                <p class="text-gray-500 mt-1">Register to access the system</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                    <select name="sex" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                    <input type="date" name="dob" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="text" name="phone_number" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                    <textarea name="address" rows="2" required 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Register
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>