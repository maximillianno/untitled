jQuery(document).ready(function($){
    $('.commentlist li').each(function (i) {
         $(this).find('div.commentNumber').text('#' + (i + 1));
    });
    $('#commentform').on('click', '#submit', function (e) {
        e.preventDefault();
        var comParent = $(this);
        $('.wrap_result')
            .css('color', 'green')
            .text('Сохранение комментария')
            .fadeIn(500, function(){
                var data = $('#commentform').serializeArray();
                $.ajax({
                    url: $('#commentform').attr('action'),
                    data: data,
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (html) {


                        if(html.error){

                            $('.wrap_result').css('color', 'red').append('<br><strong>Ошибка: </strong>' + html.error.join('<br>'));
                            $('.wrap_result').delay(2000).fadeOut(500);



                        } else  {

                            $('.wrap_result')
                                .append('<br><strong>Сохранено!</strong>')
                                //.append(html.toString())
                                .delay(2000)
                                .fadeOut(500, function () {
                                    if (html.parent_id > 0) {
                                        comParent.parents('#respond').prev().after('<ul class="children">' + html.comment + '</ul>');
                                        alert('parent>0');

                                    } else {
                                        if (document.getElementById("blockofcomments")) {

                                             alert('dsfjshdf');
                                            $('ol.commentlist').append(html.comment);
                                        } else {
                                            alert('no blockofcomment');
                                            $('#respond').before('<ol class="commentlist group">' + html.comment + '</ol>');
                                        }

                                    }
                                    alert('fadeout');

                                    //$('#cancel-comment-reply-link').click();
                                });
                            $('#cancel-comment-reply-link').click();
                        }
                        


                    },
                    error: function () {
                        $('.wrap_result').css('color', 'red').append('<br><strong>Ошибка: </strong>');
                        $('.wrap_result').delay(2000).fadeOut(500, function () {
                            $('#cancel-comment-reply-link').click();
                        });


                    }
                });
            });

    });
});