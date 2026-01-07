var $ = require('jquery');
window.$ = $;
window.jQuery = $;

let addCache = function() {
    if ($("#cache").length !== 0) {
        return true;
    }
    $("body").append("<div id='cache' style='position:fixed;top:0px; height: 100%; width: 100%; background: rgba(0,0,0,0.3); z-index: 9999;text-align:center;padding-top:25%'><div class='loader'><img src=" + srcImgLoader + " alt=''></div></div>");
}

let removeCache = function() {
    $("#cache").remove();
}

//When modal is closed
$(".modal-movie").on('hidden.bs.modal', function (e) {
    var id = $(this).data("id");
    $('.content-'+id).empty();
});

//Click on checkbox, load movie list
$(document).on('click', 'input:checkbox', function(event){
    addCache();
    localStorage.removeItem('lastUrl');
    var group = "input:checkbox[name='" + $(this).prop("name") + "']";
    $(group).prop("checked", false);
    $(this).prop("checked", true);
    var limitSelected = $('select#limit option:selected').val();
    var url = 'moviesByGenre/'+$(this).val()+'?limit='+limitSelected;

    getTopMovieFromGenreSelected($(this).val());

    setTimeout(() => {
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                if(data) {
                    $('#listMovies').html(data);
                    localStorage.setItem('lastUrl', url);
                }
                removeCache();
            }
        });
    });
});

//Open popup when click more detail
$(document).on('click', '.detailMovie', function(event){
    addCache();
    var id = $(this).data("id");
    $('#modal-movie-'+id).show();
    var url = 'detailMovie/'+id;

    setTimeout(() => {
        $.ajax({
            async:true,
            type: 'get',
            url: url,
            success: function (data) {
                if(data) {
                    $('.content-'+id).html(data);
                }
                removeCache();
            }
        });
    });
})

//Load the main trailer
function getTopMovieFromGenreSelected(genre){
    var url = 'topMovie/'+genre;
    setTimeout( () => {
        $.ajax({
            async:true,
            type: 'get',
            url: url,
            success: function (data) {
                if(data) {
                    $('.frame-trailer').html(data);
                }
            }
        })
    },50);
}

//Find Movie
$(document).on('click', '#search-bar-icon', function(event){
    var textToFind = $('input#search-bar').val();
    if(textToFind?.trim()!==""){
        addCache();
        localStorage.removeItem('lastUrl');
        var url = 'findMovie/'+textToFind;

        setTimeout(() => {
            $.ajax({
                type: 'get',
                url: url,
                success: function (data) {
                    if(data) {
                        $('#listMovies').html(data);
                        $('.genreCheckbox').prop("checked", false);
                        $('input#search-bar').val('');
                        $('select#limit').val(5);
                        localStorage.setItem('lastUrl', url);
                    }
                    removeCache();
                }
            });
        }, 50)
    }
});

$(document).on('keydown', '#formMovie', function(event){
    if(event.keyCode === 13) {
        event.preventDefault();
        $('#search-bar-icon').trigger('click');
        return false;
    }
});

// Clic on link pagination
document.addEventListener('click', function (e) {
    const link = e.target.closest('.pagination a');
    if (!link) return;
    e.preventDefault();

    addCache();
    setTimeout(() => {
        fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                document.querySelector('#listMovies').innerHTML = html;
            })
            .finally(() => {removeCache()});
    });
});

// Change number of items per page
document.querySelector('#limit').addEventListener('change', function(e) {
    const limit = this.value;
    const lastUrl = new URL(localStorage.getItem('lastUrl'), window.location.origin);
    let genreSelected = $('input[name="genreCheckbox"]:checked').val();
    lastUrl.pathname = '/moviesByGenre/' + genreSelected;
    lastUrl.searchParams.set('limit', limit);
    const url = lastUrl.toString();

    addCache();
    setTimeout(() => {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => document.querySelector('#listMovies').innerHTML = html)
            .finally(() => { removeCache(); });
    }, 50);
});
