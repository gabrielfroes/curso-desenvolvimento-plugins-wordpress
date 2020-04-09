(function ($) {
    $(function () {
        MyYoutubeRecommendation.loadVideos(my_yt_rec_ajax.url).then((value) => {
            MyYoutubeRecommendation.listCallbacks.forEach((item) => {
                item.callback(value, item.container, item.layout, item.limit, item.lang);
            })
        });
    });
})(jQuery);