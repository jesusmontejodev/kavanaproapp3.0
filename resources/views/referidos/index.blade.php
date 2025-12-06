<x-app-layout>

    <h2 class="text-xl font-bold mb-4">Tu enlace de referido</h2>

    <input type="text" value="{{ $linkReferido }}" class="w-full p-2 border rounded" readonly>

    <h3 class="text-lg font-semibold mt-6">Tus referidos</h3>

    @if($misReferidos->count() == 0)
        <p class="text-gray-600">Aún no tienes referidos.</p>
    @else
        <table class="w-full mt-4 border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Nombre</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($misReferidos as $ref)
                    <tr class="border-t">
                        <td class="p-2">{{ $ref->referido->name }}</td>
                        <td class="p-2">{{ $ref->referido->email }}</td>
                        <td class="p-2">{{ $ref->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</x-app-layout>
