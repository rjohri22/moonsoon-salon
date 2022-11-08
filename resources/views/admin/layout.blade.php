@include('admin.header')

@yield('css')
<style>
    .h-p-bold-gray{
        color: #808080; font-weight: 500; padding-left: 10px;
    }
    .h-bold-gray{
        color: #696969; font-weight: 600;
    }
</style>
@yield('content')
@include('admin.footer')
@yield('js')
