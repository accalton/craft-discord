import React from 'react';
import Select from '../Select';
import TextInput from '../TextInput';

const EmojiText = props => {
    const { emojis, id, onChange, removeRow } = props;

    const handleClick = id => {
        removeRow(id);
    }

    return (
        <div className="matrixblock">
            <div className="titlebar">
                <div className="blocktype">Role React</div>
            </div>
            <div className="actions">
                <a className="error" data-icon="remove" type="button" role="button" onClick={handleClick}>Delete</a>
            </div>
            <div className="fields">
                <div className="flex-fields">
                    <Select name={`emoji[${id}]`} label="Emoji" width="50" options={emojis} onChange={onChange} />
                    <TextInput name={`text[${id}]`} label="Text" width="50" onChange={onChange} />
                </div>
            </div>
        </div>
    );
}

export default EmojiText;