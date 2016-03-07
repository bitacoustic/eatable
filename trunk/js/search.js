/*
 * search.js
 * 
 * JavaScript or jQuery code common to all views
 */

function submitSearch() {
    $('#dateid').val($('#selectdatesearch').val());
    document.getElementById("searchForm").submit();
}

var dateToday = new Date();
$('#datetimepicker_search_date').datetimepicker({
    format: 'MM-DD-YYYY',
    minDate: dateToday
});

$('#datetimepicker_search_time').datetimepicker({
    format: 'h:mm A', // e.g. 7:00 PM
    stepping: 30       // 30 minute intervals
});

