class MyYoutubeRecommendation {

    static data = {};
    static containerId = "";
    static limit;
    static max_height;

    static async loadVideos() {
        console.log(`%cMy Youtube Recommendation: Loading data from AJAX'`, "background:green;color:white");

        const postData = {
            action: 'my_youtube_recommendation_videos'
        };
        jQuery.post(my_yt_rec_ajax.url, postData, function (response) {
            MyYoutubeRecommendation.data = JSON.parse(response);
            MyYoutubeRecommendation.buildList();
        });

    }

    static buildList() {

        let myData = MyYoutubeRecommendation.data;

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
        if (MyYoutubeRecommendation.limit != null) videos = myData.videos.slice(0, MyYoutubeRecommendation.limit);
        for (let i = 0; i < videos.length; i++) {
            theList.appendChild(MyYoutubeRecommendation.buildListItem(videos[i], channelElements));
        }

        let container = document.querySelector(`#${MyYoutubeRecommendation.containerId}`);
        container.innerHTML = '';
        container.appendChild(theList.cloneNode(true));
    }

    static buildListItem(item, channel) {
        let theItem = document.createElement('div');
        let theTitle = document.createElement('p');
        let theThumb = document.createElement('img');
        let theThumbLink = document.createElement('a');
        let theTitleLink;

        // Links
        theThumbLink.href = item.link;
        theThumbLink.target = '_blank';
        theThumbLink.title = item.title;
        theTitleLink = theThumbLink.cloneNode();

        // Title
        theTitle.className = 'my-yt-rec-title';
        theTitle.textContent = item.title;

        // Thumb
        theThumb.className = 'my-yt-rec-thumbnail';
        theThumb.src = item.thumbnail;

        theThumbLink.appendChild(theThumb);
        theTitleLink.appendChild(theTitle)

        // Item
        theItem.className = "my-yt-rec-item";
        theItem.appendChild(theThumbLink);
        theItem.appendChild(channel['avatar'].cloneNode(true));
        theItem.appendChild(theTitleLink);

        return theItem;
    }
};