//console.log(rows)
var $table = $('#table')

$(function () {
  $table.bootstrapTable({ data: rows })

  $(".file_replace_form button").on("click", function (e) {
    let target = $(e.target)
    let form = target.closest("form")
    let file = $(form.find(':file')[0])
    file.trigger("click");
  });


})


//  Formatters START ----------------



function fileFormatter(value, row) {
  return `<b>${value.replace(/^.*[\\\/]/, '')}</b>`
}

function previewfileFormatter(value, row) {
  return `<a target="_blank" href="${value}"><button class="btn btn-sm btn-outline-info">Preview File</button></a>`
}

function replacefileFormatter(value, row) {
  return `
      <span>
        <form class="file_replace_form" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="objectID" value="${value}">
          <input type="hidden" name="task" value="replaceFile">
          <input onchange="form.submit()" style="display:none" type="file" name="file">
          <button class="btn btn-sm btn-outline-warning" type="button">
            Replace File
          </button>
        </form>
      </span>
    `
}
function deletefileFormatter(value, row) {
  return `
      <span>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="objectID" value="${value}">
          <input type="hidden" name="task" value="deleteFile">
          <button class="btn btn-sm btn-outline-danger" type="submit">
            Delete File
          </button>
        </form>
      </span>
    `}

function editmetadataFormatter(value, row) {
  return `<a target="_blank" href="pages/metatag/metatag_form.php?id=${value}"><button class="btn btn-sm btn-outline-info">Edit Meta Data</button></a>`
}

// formatters END ---------------