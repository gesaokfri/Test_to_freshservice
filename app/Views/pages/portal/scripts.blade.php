<!-- START: Script -->
<script src="{{base_url('assets/libs')}}/jquery/jquery.min.js"></script>
<script src="{{base_url('assets/libs')}}/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{base_url('assets/libs')}}/metismenu/metisMenu.min.js"></script>
<script src="{{base_url('assets/libs')}}/simplebar/simplebar.min.js"></script>
<script src="{{base_url('assets/libs')}}/node-waves/waves.min.js"></script>
<script src="{{base_url('assets/js')}}/app.js"></script>
<script src="{{base_url('assets/libs')}}/sweetalert2/sweetalert2.min.js"></script>
<script type="text/javascript" src="{{base_url('')}}/assets/libs/apexcharts/apexcharts.min.js"></script>

{{-- On Load --}}
<script src="{{base_url('')}}/assets/libs/loading-bar/vendor/scripts/skylo.js"></script>

<!-- Select2 -->  
<script type="text/javascript" src="{{base_url('')}}/assets/libs/select2/js/select2.min.js"></script>

{{-- Bootstrap Datepicker --}}
<script type="text/javascript" src="{{base_url('')}}/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{base_url('')}}/assets/libs/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js"></script>

{{-- Jquery Mask --}}
<script src="{{base_url('')}}/assets/libs/mask/jquery.mask.min.js"></script>

<script>
    $(document).ready(function (){
        $('#loader').fadeOut();
    });

    function getTypeChart(fromID,divId,toID){
        var data = $("#"+fromID).val();
        if(data){
            if(data=="tahun" || data=="quarter"){
                $("#"+toID).val("");
                $("#"+toID).prop('required',false);
                $("#"+divId).hide();
            }
            else {
                $("#"+toID).val("");
                $("#"+toID).prop('required',true);
                $("#"+divId).show();
            }
        }
        else {
            $("#"+toID).val("");
            $("#"+divId).hide();
        }
    }
</script>