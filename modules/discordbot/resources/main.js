import axios from 'axios';

const guildDropdown = document.getElementById('fields-guild');
const channelDropdown = document.getElementById('fields-channel');

guildDropdown.addEventListener('change', function (e) {
    let guildId = e.target.value;

    if (guildId) {
        axios.get('/admin/actions/discordbot/guild/channels', {
            params: {
                'guildId': guildId
            }
        }).then(function (response) {
            let channels = response.data;
            let options = '<option value="">---</option>';
            for (let channel in channels) {
                options += '<option value="' + channel + '">' + channels[channel] + '</option>';
            }
            channelDropdown.innerHTML = options;
        });
    } else {
        channelDropdown.innerHTML = '<option value="">---</option>';
    }
});
