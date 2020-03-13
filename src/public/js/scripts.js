let my_youtube_rec_container_id = "";

function my_yt_rec_init(container_id) {
    console.log(`%cMy Youtube Recommendation: Starting at '${container_id}'`, "background:green;color:white");

    my_youtube_rec_container_id = container_id;

    let url = 'http://localhost:8080/wp-content/uploads/my-youtube-recommendation/videos.json';
    fetch(url)
        .then(res => res.json())
        .then((out) => {
            buildList(out);
        })
        .catch(err => {
            console.error("%cMy Youtube Recommendation Error: JSON File was not load.", "background:pink;color:red");
        });
}

function buildList(list) {

    let theListTitle = document.createElement('h3');
    let theList = document.createElement('div');

    // Channel Info
    let channelName = list['channel'].name;
    let channelLink = list['channel'].link;
    let channelAvatar = document.createElement('img');
    channelAvatar.src = list['channel'].avatar;
    channelAvatar.className = 'my-yt-rec-avatar';

    let channelElements = Array();
    channelElements['name'] = channelName;
    channelElements['link'] = channelLink;
    channelElements['avatar'] = channelAvatar;

    // Title
    theListTitle.textContent = 'Meus Vídeos';

    // Full List
    theList.className = 'my-yt-rec'
    //theList.appendChild(theListTitle);

    for (let i = 0; i < list['videos'].length; i++) {
        theList.appendChild(buildListItem(list['videos'][i], channelElements));
    }
    document.querySelector(`#${my_youtube_rec_container_id}`).appendChild(theList.cloneNode(true));
}

function buildListItem(item, channel) {
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