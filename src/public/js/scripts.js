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

    // Time descripton change function
    timeAgo(dateParam) {
        if (!dateParam) return null;

        const date =
            typeof dateParam === "object" ? dateParam : new Date(dateParam);
        const DAY_IN_MS = 86400000; // 24 * 60 * 60 * 1000
        const today = new Date();
        const yesterday = new Date(today - DAY_IN_MS);
        const seconds = Math.round((today - date) / 1000);
        const minutes = Math.round(seconds / 60);
        const hours = Math.round(minutes / 60);
        const days = Math.round(hours / 24);
        const weeks = Math.round(days / 7);
        const months = Math.round(weeks / 4);
        const years = Math.round(months / 12);

        if (seconds < 5) {
            return "agora";
        } else if (seconds < 60) {
            return `${seconds} segundos atrás`;
        } else if (seconds < 90) {
            return "há aproximadamente 1 min";
        } else if (minutes < 60) {
            return minutes == 1 ? `há ${minutes} minuto` : `há ${minutes} minutos`;
        } else if (hours < 24) {
            return hours == 1 ? `há ${hours} hora` : `há ${hours} horas`;
        } else if (days < 31) {
            return days == 1 ? `há ${days} dia` : `há ${days} dias`;
        } else if (weeks < 4) {
            return weeks == 1 ? `há ${weeks} semana` : `há ${weeks} semanas`;
        } else if (months < 12) {
            return months == 1 ? `há ${months} mês` : `há ${months} meses`;
        } else {
            return years == 1 ? `há ${years} anos` : `há ${years} anos`;
        }
    },

    buildList(
        jsonData,
        containerId,
        layout = "grid",
        limit = 15,
        lang = "en_US"
    ) {
        const myData = jsonData;

        let theList = document.createElement("div");

        theList.className = (layout == "list") ? "my-yt-rec-list" : "my-yt-rec";

        let videos = {};
        if (limit != null) videos = myData.videos.slice(0, limit);
        for (let i = 0; i < videos.length; i++) {
            theList.appendChild(
                MyYoutubeRecommendation.buildListItem(videos[i], myData.channel)
            );
        }

        let container = document.querySelector(`#${containerId}`);
        container.innerHTML = "";
        container.appendChild(theList);
    },

    buildListItem(item, channel) {
        const theItem = document.createElement("div");

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
                            <span>${item.views} visualizações • ${this.timeAgo(item.published)}</span>
                        </div>
                    </div>
                </div>
            </div>`;

        return theItem;
    },
};