import React, { useState, useEffect } from 'react';
import Select from './molecules/fields/Select';
import TextInput from './molecules/fields/TextInput';

const PenPen = () => {
    const [channels, setChannels] = useState([]);
    const [emojis, setEmojis] = useState([]);
    const [guilds, setGuilds] = useState([]);
    const [roles, setRoles] = useState([]);
    const [state, setState] = useState({});
    const discordToken = process.env.DISCORD_TOKEN;

    const getChannels = () => {
        setChannels([]);

        if (state.guild) {
            axios.get('/admin/actions/penpen/guild/channels', {
                params: { guildId: state.guild }
            }).then(response => {
                setChannels(response.data);
            });
        }
    }

    const getGuilds = () => {
        setGuilds([]);

        if (state.token) {
            axios.get('/admin/actions/penpen/member/guilds').then(response => {
                setGuilds(response.data);
            });
        }
    }

    const onChange = event => {
        let newState = {...state};
        newState[event.target.name] = event.target.value;
        setState(newState);
    }

    useEffect(() => {
        let newState = {...state};
        newState = {
            token: discordToken
        }
        setState(newState);
    }, []);

    useEffect(() => {
        getGuilds();
    }, [state.token]);

    useEffect(() => {
        getChannels();
    }, [state.guild]);

    return (
        <form className="flex-fields" method="POST" action="/admin/actions/discordbot/discord/post">
            <TextInput name="title" label="Title" width="100" onChange={onChange} />
            <Select name="guild" label="Guild" width="50" options={guilds} onChange={onChange} />
            <Select name="channel" label="Channel" width="50" options={channels} onChange={onChange} />
        </form>
    );
};

export default PenPen;