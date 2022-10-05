import React, { useContext, useEffect, useState } from 'react';
import { DiscordContext } from './config/discord-context';
import ColorPicker from './Fields/ColorPicker';
import Matrix from './Fields/Matrix';
import Select from './Fields/Select';
import TextArea from './Fields/TextArea';
import TextInput from './Fields/TextInput';

const Discord = () => {
    const [state, setState] = useState({});
    const [channels, setChannels] = useState([]);
    const [emojis, setEmojis] = useState([]);
    const [guilds, setGuilds] = useState([]);
    const [roles, setRoles] = useState([]);

    const getChannels = event => {
        setChannels([]);

        if (state.guildId) {
            axios.get('/admin/actions/discordbot/guild/channels', {
                params: {
                    guildId: state.guildId
                }
            }).then(response => {
                setChannels(response.data);
            });
        }
    }

    const getEmojis = () => {
        setEmojis([]);

        if (state.guildId) {
            axios.get('/admin/actions/discordbot/guild/emojis', {
                params: {
                    guildId: state.guildId
                }
            }).then(response => {
                setEmojis(response.data);
            });
        }
    }

    const getGuilds = () => {
        axios.get('/admin/actions/discordbot/member/guilds')
            .then(response => {
                setGuilds(response.data);
            });
    }

    const setGuild = event => {
        let guildId = event.target.value;
        setState({ guildId: guildId });
    }

    const getRoles = () => {
        setRoles([]);

        if (state.guildId) {
            axios.get('/admin/actions/discordbot/guild/roles', {
                params: {
                    guildId: state.guildId
                }
            }).then(response => {
                setRoles(response.data);
            });
        }
    }

    useEffect(() => getGuilds(), []);
    useEffect(() => {
        getChannels();
        getEmojis();
        getRoles();
    }, [state]);

    return (
        <DiscordContext.Provider value={state}>
            <form className="flex-fields" method="POST" action="/admin/actions/discordbot/guild/post">
                <TextInput name="title" label="Title" width="100" />
                <Select name="guild" label="Guild" width="50" options={guilds} onChange={setGuild} />
                <Select name="channel" label="Channel" width="50" options={channels} />
                <hr />
                <ColorPicker name="color" label="Color" width="25" />
                <TextArea name="description" label="Description" width="75" />
                <hr />
                <Matrix name="roles" label="Roles" width="100" roles={roles} emojis={emojis} />
                <input type="submit" />
            </form>
        </DiscordContext.Provider>
    );
}

export default Discord;