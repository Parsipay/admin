$(document).ready(function () {


function sortTableByUnixJQ(order = 'desc') {
  let $rows = $("#tablesearch tr").get();

  $rows.sort(function(a, b) {
    let aTime = parseInt($(a).data("item-unix"));
    let bTime = parseInt($(b).data("item-unix"));
    return order === 'asc' ? aTime - bTime : bTime - aTime;
  });

  $.each($rows, function(index, row) {
    $("#tablesearch").append(row);
  });

  updateSortIcons(order);
}

});
