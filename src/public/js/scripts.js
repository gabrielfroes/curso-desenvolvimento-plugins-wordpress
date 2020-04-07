const MyYoutubeRecommendation = {

    callBacks: [],

    async loadVideos(url) {
        console.log(`%cMy Youtube Recommendation: Loading data from JSON'`, "background:green;color:white");

        const postData = {
            action: 'my_youtube_recommendation_videos'
        };

        let request = jQuery.ajax({
            method: "GET",
            url: url,
            data: postData,
            dataType: "json"
        })

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

    buildList(jsonData, containerId, layout = 'grid', limit = 15, lang = 'en_US') {

        const myData = jsonData;

        let theListTitle = document.createElement('h3');
        let theList = document.createElement('div');

        // Channel Info
        let channelName = myData.channel.name;
        let channelLink = myData.channel.link;
        let channelAvatar = document.createElement('img');
        channelAvatar.src = myData.channel.avatar;
        channelAvatar.className = 'my-yt-rec-avatar';

        let channelElements = Array();
        channelElements.name = channelName;
        channelElements.link = channelLink;
        channelElements.avatar = channelAvatar;

        // Title
        theListTitle.textContent = 'Meus Vídeos';

        // Full List
        theList.className = 'my-yt-rec';
        //theList.appendChild(theListTitle);

        let videos = {};
        if (limit != null) videos = myData.videos.slice(0, limit);
        for (let i = 0; i < videos.length; i++) {
            theList.appendChild(MyYoutubeRecommendation.buildListItem(videos[i], channelElements));
        }

        let container = document.querySelector(`#${containerId}`);
        container.innerHTML = '';
        container.appendChild(theList.cloneNode(true));

    },

    buildListItem(item, channel) {
        let theItem = document.createElement("div");
        let theDetails = document.createElement("div");
        let theMeta = document.createElement("div");
        let theMetaBlock = document.createElement("div");
        let theChannel = document.createElement("div");
        let theMoreInformations = document.createElement("div");
        let theInformations = document.createElement("span");
        let theTitle = document.createElement("div");
        let theThumbBox = document.createElement("div");
        let theThumb = document.createElement("img");
        let theThumbLink = document.createElement("a");
        let theTitleLink;

        // Links
        theThumbLink.href = item.link;
        theThumbLink.target = "_blank";
        theThumbLink.title = item.title;
        theTitleLink = theThumbLink.cloneNode();

        // Thumb
        theThumb.className = "my-yt-rec-thumbnail";
        theThumb.src = item.thumbnail;
        theThumbLink.appendChild(theThumb);
        theThumbBox.appendChild(theThumbLink);

        // Title
        theTitle.className = "my-yt-rec-title";
        theTitle.textContent = item.title;

        theTitleLink.appendChild(theTitle);

        //Channel
        theChannel.className = "my-yt-rec-channel";
        theChannel.textContent = channel.name;

        //Views and Published Time
        theInformations.textContent =
            item.views + " visualizações • " + this.timeAgo(item.published);

        theMoreInformations.className = "my-yt-rec-more-informations";
        theMoreInformations.appendChild(theChannel);
        theMoreInformations.appendChild(theInformations);

        //Make layout
        theMetaBlock.className = "my-yt-rec-metablock";
        theMetaBlock.appendChild(theChannel);
        theMetaBlock.appendChild(theMoreInformations);

        theMeta.className = "my-yt-rec-meta";
        theMeta.appendChild(theTitleLink);
        theMeta.appendChild(theMetaBlock);

        theDetails.className = "my-yt-rec-details";
        theDetails.appendChild(channel["avatar"].cloneNode(true));
        theDetails.appendChild(theMeta);

        // Item
        theItem.className = "my-yt-rec-item";
        theItem.appendChild(theThumbBox);
        theItem.appendChild(theDetails);

        return theItem;
    },

}