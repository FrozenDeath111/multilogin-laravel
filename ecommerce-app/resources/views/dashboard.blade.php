<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="text-xl font-bold text-indigo-600">My Ecom Site!</div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 text-sm">Hi, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">Account Overview</h1>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Email Address</p>
                    <p class="text-gray-800">{{ auth()->user()->email }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Member Since</p>
                    <p class="text-gray-800">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Your Recent Orders</h2>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full">
                    {{ count($orders) }} Total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Product</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order['id'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order['product'] }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-medium 
                                                                                                                {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">${{ $order['quantity'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">${{ $order['price'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    You haven't placed any orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 my-8 border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">To see food orders from FoodPanda, <a href="/sso-foodpanda"
                    target="_blank" class="text-blue-900">Click Here!!!</a></h1>
        </div>
    </main>

</body>

</html>