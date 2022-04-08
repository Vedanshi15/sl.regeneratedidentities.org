function addRow() {
  let andor = `<label>Select Operater</label>
  <select name="andor[]" class="form-control">
    <option value="AND">AND</option>
    <option value="OR">OR</option>
  </select>`
  let searchRow = $(".search-row").eq(0).clone();
  $(searchRow).find(".andor-container").html(andor);

  $($(".search-row-container")[0]).append($(searchRow));
}