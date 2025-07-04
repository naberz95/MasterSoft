<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Acta #{{ $acta->numero }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <p><strong>Tipo:</strong> {{ $acta->tipo->nombre ?? '' }}</p>
        <p><strong>Fecha:</strong> {{ $acta->fecha }}</p>
        <p><strong>Lugar:</strong> {{ $acta->lugar }}</p>
        <p><strong>Cliente:</strong> {{ $acta->cliente->nombre ?? '' }}</p>
        <p><strong>Proyecto:</strong> {{ $acta->proyecto->nombre ?? '' }}</p>
        <p><strong>Firmante Cliente:</strong> {{ $acta->firmanteCliente->nombre ?? '' }}</p>
        <p><strong>Firmante Gestión y Proyectos:</strong> {{ $acta->firmanteGp->nombre ?? '' }}</p>

        <h3 class="mt-6 font-semibold">Objetivo</h3>
        <p>{{ $acta->objetivo }}</p>

        <h3 class="mt-6 font-semibold">Agenda</h3>
        <p>{{ $acta->agenda }}</p>

        <h3 class="mt-6 font-semibold">Desarrollo</h3>
        <p>{{ $acta->desarrollo }}</p>

        <h3 class="mt-6 font-semibold">Conclusiones</h3>
        <p>{{ $acta->conclusiones }}</p>

        @if($acta->proxima_reunion)
        <p><strong>Próxima Reunión:</strong> {{ $acta->proxima_reunion }}</p>
        @endif
    </div>
</x-app-layout>
