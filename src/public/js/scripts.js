const MyYoutubeRecommendation = {
    listCallbacks: [],

    async loadVideos(url) {
        console.log(
            `%cMy Youtube Recommendation: Loading data from JSON'`,
            "background:green;color:white"
        );

        const postData = {
            action: "my_youtube_recommendation_videos",
        };

        let request = jQuery.ajax({
            method: "GET",
            url: url,
            data: postData,
            dataType: "json",
        });

        return await request.done();
    },

    timeLang(number, time, lang) {
        let en_US = {
            now: "now",
            seconds: `${number} seconds ago`,
            minute: `${number} minute ago`,
            minutes: `${number} minutes ago`,
            hour: `${number} hour ago`,
            hours: `${number} hours ago`,
            day: `${number} day ago`,
            days: `${number} days ago`,
            week: `${number} week ago`,
            weeks: `${number} weeks ago`,
            month: `${number} month ago`,
            months: `${number} months ago`,
            year: `${number} year ago`,
            years: `${number} years ago`,
        };

        let pt_BR = {
            now: "agora",
            seconds: `há ${number} segundos`,
            minute: `há ${number} minuto`,
            minutes: `há ${number} minutos`,
            hour: `há ${number} hora`,
            hours: `há ${number} horas`,
            day: `há ${number} dia`,
            days: `há ${number} dias`,
            week: `há ${number} semana`,
            weeks: `há ${number} semanas`,
            month: `há ${number} mês`,
            months: `há ${number} meses`,
            year: `há ${number} ano`,
            years: `há ${number} anos`,
        };

        const langs = {
            en_US: en_US,
            pt_BR: pt_BR
        };

        const result = langs[lang];
        return result[time];
    },

    // Time descripton change function
    timeAgo(date, lang) {
        date = typeof date === "object" ? date : new Date(date);

        if (!date) return null;

        const intervals = [{
                label: "year",
                seconds: 31536000
            },
            {
                label: "month",
                seconds: 2592000
            },
            {
                label: "week",
                seconds: 604800
            },
            {
                label: "day",
                seconds: 86400
            },
            {
                label: "hour",
                seconds: 3600
            },
            {
                label: "minute",
                seconds: 60
            },
            {
                label: "second",
                seconds: 5
            },
            {
                label: "now",
                seconds: 0
            },
        ];

        const seconds = Math.floor((Date.now() - date.getTime()) / 1000);
        const interval = intervals.find((i) => i.seconds < seconds);
        const count = Math.floor(seconds / interval.seconds);
        interval.label += count !== 1 ? 's' : '';
        return (this.timeLang(count, interval.label, lang));

    },

    buildList(jsonData, containerId, layout = "grid", limit = 15, lang = "en_US") {
        const myData = jsonData;
        let theList = document.createElement("div");

        theList.className = layout == "list" ? "my-yt-rec-list" : "my-yt-rec";

        let videos = {};
        if (limit != null) videos = myData.videos.slice(0, limit);
        for (let i = 0; i < videos.length; i++) {
            theList.appendChild(
                MyYoutubeRecommendation.buildListItem(videos[i], myData.channel, lang)
            );
        }

        let container = document.querySelector(`#${containerId}`);
        container.innerHTML = "";
        container.appendChild(theList);
    },

    buildListItem(item, channel, lang) {
        const theItem = document.createElement("div");
        let viewsText = {
            pt_BR: 'visualizações',
            en_US: 'views'
        }
        theItem.className = "my-yt-rec-item";

        theItem.innerHTML = `
            <div>
                <a href="${item.link}" target="_blank" title="${item.title}">
                <img class="my-yt-rec-thumbnail" src="${item.thumbnail}">
                </a>
            </div>
            <div class="my-yt-rec-meta"><img src="${channel.avatar}" class="my-yt-rec-avatar">
                <div class="my-yt-rec-meta-data">
                  <h3 class = "my-yt-rec-title">
                    <a href="${item.link}" target="_blank" title="${item.title}">
                        ${item.title}
                    </a>
                  </h3>
                    <div class="my-yt-rec-meta-block">
                        <div class="my-yt-rec-channel">${channel.name}</div>
                        <div class="my-yt-rec-meta-line">
                            <span>${item.views} ${viewsText[lang]} • ${this.timeAgo(item.published, lang)}</span>
                        </div>
                    </div>
                </div>
            </div>`;

        return theItem;
    },
};