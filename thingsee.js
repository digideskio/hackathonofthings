$( document ).ready(function() {
    var highestLuck = null;
    var $highestRow = null;
    $('#horse_standing').DataTable({
        fnRowCallback: function(row, aData) {
            if (highestLuck == null || parseFloat(aData[5]) > highestLuck) {
                highestLuck = parseFloat(aData[5]);
                $highestRow = $(row);
            }
        }
    });
    $highestRow.addClass('highest_luck');
    $('.dataTables_length').html('<label>Insights</label>');
});