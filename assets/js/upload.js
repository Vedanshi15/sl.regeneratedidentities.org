$(function () {

  // clear "files_id" from localstorage
  localStorage.removeItem("files_id")

  // First register any plugins
  FilePond.registerPlugin(FilePondPluginImagePreview);
  FilePond.registerPlugin(FilePondPluginFileValidateType);


  FilePond.setOptions({
    server: {
      instantUpload: false,
      process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {

        let target = $($('.my-pond').find("#filepond--item-" + metadata.id)[0])
        let title = target.find("input").val()

        const formData = new FormData();
        formData.append(fieldName, file, file.name);
        formData.append("title", title)

        const request = new XMLHttpRequest();
        request.open('POST', 'process.php');
        request.upload.onprogress = (e) => {
          progress(e.lengthComputable, e.loaded, e.total);
        };

        request.onload = function () {
          console.log(request.status);
          if (request.status >= 200 && request.status < 300) {
            console.log(this.response);
            let ok = false;
            try {
              let parsed = JSON.parse(this.response);
              console.log(parsed['status']);
              if (parsed['status'] == "success") {
                ok = true;
                let storage = JSON.parse(localStorage.getItem("files_id") ?? "[]");
                storage.push(parsed['key']);
                localStorage.setItem("files_id", JSON.stringify(storage))
              }
            } catch (e) {
              ok = false;
              console.log('file error', e);
            }
            if (ok) {
              load(request.responseText);
            } else {
              error("Something went wrong!")
            }
          } else {
            error('oh no');
          }
        };

        request.send(formData);

        return {
          abort: () => {
            request.abort();
            abort();
          },
        };
      },
    },
  })

  // Turn input element into a pond
  $('.my-pond').filepond({
    acceptedFileTypes: ['application/pdf', 'image/tiff', 'image/png', 'image/jpeg'],
    credits: false,
    allowRevert: false,
    allowReplace: false,
    allowProcess: false,
    allowRemove: true,
    instantUpload: false,
    allowMultiple: true,
  });

  // Listen for addfile event
  $('.my-pond').on('FilePond:addfile', function (e) {
    console.log('file added event', e);
    let details = e.detail
    details.file.setMetadata("id", details.file.id)
    console.log(details)
    if (!details.error) {
      // debugger
      let input = `<input required tabindex="5" placeholder="Title..." type="text" class="form-control form-control-xs fileTitleInput">`
      setInputTitle(details.file, input)
    }
  });

  $(".my-pond").on('FilePond:processfiles', function (e) {
    Swal.fire({
      title: 'All files are uploaded.',
      text: "Do you want to add more files?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, I\'ll add more files',
      cancelButtonText: 'Take me to metatag page',
    }).then((result) => {
      if (!result.isConfirmed) {
        let ids = JSON.parse(localStorage.getItem("files_id"));
        window.location = `edit_metatag.php`
        localStorage.removeItem("files_id")
      }
    })
  })

  function setInputTitle(x, input) {
    let id = x.id

    let target = $($('.my-pond').find("#filepond--item-" + id)[0])
    let info = $(target.find(".filepond--file-info")[0])
    let infoFilename = $(info.find(".filepond--file-info-main")[0])

    let link = URL.createObjectURL(x.file)

    input = input ? input : $(info.find(".fileTitleInput")[0])
    $(info.find(".fileTitleInput")[0]).remove();

    infoFilename.html(
      `<a target="_blank" href=${link}>${infoFilename.html()}</a>`
    )

    info.html(`<div style="display:flex;flex-direction:column">${info.html()}</div>`);
    info.append(input);
  }

  function setInputTitleFiles() {
    let pond = FilePond.find($('.my-pond')[0])
    files = pond.getFiles()
    files.forEach(x => {
      setInputTitle(x)
    });
  }


  $("#fileForm").on("submit", function (e) {
    e.preventDefault();
    let pond = FilePond.find($('.my-pond')[0])
    pond.processFiles();
  })

  $("#resetFileForm").on("click", function (e) {
    e.preventDefault();
    let pond = FilePond.find($('.my-pond')[0])
    pond.removeFiles();
  })

});