<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Locker Logs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-6xl mx-auto py-8 px-4">

    <h1 class="text-xl font-semibold mb-6">
        Locker Access Logs
    </h1>

    <div class="bg-white rounded-xl shadow-sm p-5">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Student</th>
                        <th class="px-4 py-2">Locker</th>
                        <th class="px-4 py-2">RFID</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-2">{{ $log->id }}</td>
                            <td class="px-4 py-2">
                                {{ $log->student->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $log->locker->locker_number ?? '-' }}
                            </td>
                            <td class="px-4 py-2">{{ $log->rfid_uid }}</td>

                            <td class="px-4 py-2">
                                @if ($log->status == 'success')
                                    <span class="text-green-600 font-medium">Success</span>
                                @else
                                    <span class="text-red-500 font-medium">Failed</span>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                {{ $log->access_time }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>
</div>

</body>
</html>
