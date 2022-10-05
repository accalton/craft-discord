import axios from 'axios';

console.log('foobar');

const channels = (guildId) => {
    const channelDropdown = document.getElementById('fields-channel');
    let options = '<option value="">---</option>';

    if (guildId) {
        axios.get('/admin/actions/discordbot/guild/channels')
            .then(function (response) {
                let channels = response.data;
                for (let channel in channels) {
                    options += '<option value="' + channel + '">' + channels[channel] + '</option>';
                }

                channelDropdown.innerHTML = options;
            });
    } else {
        channelDropdown.innerHTML = options;
    }
}

document.addEventListener('change', function (e) {
    if (e.target && e.target.id == 'fields-guild') {
        let guildId = e.target.value;

        axios.get('/admin/actions/discordbot/guild/set-guild-id', {
            params: {
                guildId: guildId
            }
        }).then(function () {
            channels(guildId);
        });
    }
});
