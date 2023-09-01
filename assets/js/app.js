jQuery(function ($) {
  function filterPosts() {
    let cat = $("#cat").val();
    let years = [];
    let months = [];
    $('input[name="year"]:checked').each(function () {
      years.push($(this).val());
      console.log(years);
    });
    $('input[name="month"]:checked').each(function () {
      months.push($(this).val());
      console.log(months);
    });
    var data = {
      action: "filter_posts",
      cat: cat,
      years: years,
      months: months,
    };
    $.ajax({
      url: variables.ajax_url,
      type: "POST",
      data: data,
      success: function (response) {
        $(".event_container").html(response);
      },
    });
  }

  $(document).ready(function () {
    filterPosts();
  });

  $(".filter select, .filter input[name='year']").on("change", function () {
    filterPosts();
  });
  $(".filter select, .filter input[name='month']").on("change", function () {
    filterPosts();
  });
});
