import React, { useState } from 'react';

const ColorPicker = props => {
    const { name, label, width } = props;
    const [color, setColor] = useState('');
    const [background, setBackground] = useState({});


    const onChange = (event) => {
        let hex = event.target.value;
        setColor(hex.replace('#', ''));
        const red = parseInt(hex.slice(1, 3), 16);
        const green = parseInt(hex.slice(3, 5), 16);
        const blue = parseInt(hex.slice(5, 7), 16);

        setBackground({
            backgroundColor: `rgb(${red}, ${green}, ${blue})`
        });
    }

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>
                    {label}
                </label>
            </div>
            <div className="input ltr">
                <div id="fields-color-container" className="flex color-container">
                    <div className="color">
                        <div className="color-preview" style={background}>
                            <input type="color" className="color-preview-input" onChange={onChange} />
                        </div>
                    </div>
                    <div className="color-input-container">
                        <div className="color-hex-indicator light code">#</div>
                        <input type="text" className="color-input text" name="color" size="10" defaultValue={color} />
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ColorPicker;