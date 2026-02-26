@props([
    'action' => '',
    'method' => 'POST',
    'confirmTitle' => '¿Estás seguro?',
    'confirmMessage' => 'Esta acción no se puede deshacer.',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'variant' => 'danger',
])

<form
    {{ $attributes->merge(['method' => 'POST', 'action' => $action]) }}
    x-data="{ loading: false }"
    @submit.prevent="
        $store.confirm.show({
            title: @js($confirmTitle),
            message: @js($confirmMessage),
            confirmText: @js($confirmText),
            cancelText: @js($cancelText),
            variant: @js($variant)
        }).then(ok => {
            if (ok) {
                loading = true;
                $store.loader.show();
                $el.submit();
            }
        })
    "
>
    @if(strtoupper($method) !== 'GET')
        @csrf
        @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
            @method($method)
        @endif
    @endif
    {{ $slot }}
</form>
