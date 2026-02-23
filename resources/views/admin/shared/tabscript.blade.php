@php
    /**
     * @param tabname
     * @param position (default left)
     */
@endphp
<script>
    $(document).ready(function () {
        $('#{{ $tabname }}').show();
        var tab = $('#{{ $tabname }}').kendoTabStrip({
            tabPosition: '{{ isset($position) ? $position : 'left' }}',
            animation: { open: { effects: 'fadeIn'} },
            select: tabstrip_on_tab_select_ex,
            show: tabstrip_on_tab_show,
        }).data('kendoTabStrip');
    });
    function tabstrip_on_tab_select_ex(e) {
        $('#selected-tab-index').val($(e.item).index());
        // $.ajax({
        //     type: "POST",
        //     url: '/admin/Home/SetTab',
        //     data: addAntiForgeryToken({name: '{{ $tabname }}-tab', index: $(e.item).index()})
        // });
    }
    function tabstrip_on_tab_show(e) {
        // Tab show handler - can be used for additional logic
    }
</script>
@php
$sessionKey = $tabname . '-tab';
$sessionValue = session($sessionKey, 1);
@endphp
<input type='hidden' id='selected-tab-index' name='selected-tab-index' value='{{ $sessionValue }}'>
