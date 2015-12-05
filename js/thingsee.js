$( document ).ready(function() {
    var highestLuck = null;
    var $highestRow = null;
    $('#horse_standing').DataTable({
        fnRowCallback: function(row, aData) {
            if (highestLuck == null || parseFloat(aData[5]) > highestLuck) {
                highestLuck = parseFloat(aData[5]);
                $highestRow = $(row);
            }
            if (aData[6] == 't') {
                $(row).find('td:eq( 6 )').html('<i class="fa fa-arrow-up" style="color:green;"></i>');
            } else {
                $(row).find('td:eq( 6 )').html('<i class="fa fa-arrow-down" style="color: red;"></i>');
            }
        }
    });
    $highestRow.addClass('highest_luck');
    $('.dataTables_length').html('<label style="font-weight: bold; font-size: 25px;">Insights</label>');


    $('.date-filter').on('click', function(){
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
        $('.overlay').show();
        setTimeout(function(){
            var items = [0,1,2,3,4,5];
            var item = items[Math.floor(Math.random()*items.length)];
            console.log(item);
            $('.overlay').hide();
            $('.sorting:eq('+item+')').click();
        }, 1000)
    });


});