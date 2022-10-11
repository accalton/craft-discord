import React, { useContext, useState } from 'react';

const ColorPicker = props => {
    const { name, label, width, onChange } = props;
    const [color, setColor] = useState('');
    const [background, setBackground] = useState({});

    const updateColor = (event) => {
        let hex = event.target.value;
        setColor(hex.replace('#', ''));
        const red = parseInt(color.slice(0, 2), 16);
        const green = parseInt(color.slice(2, 4), 16);
        const blue = parseInt(color.slice(4, 6), 16);

        setBackground({
            backgroundColor: `rgb(${red}, ${green}, ${blue})`
        });

        let customEvent = {
            target: {
                name: name,
                value: color
            }
        }

        onChange(customEvent);
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
                            <input type="color" className="color-preview-input" onChange={updateColor} />
                        </div>
                    </div>
                    <div className="color-input-container">
                        <div className="color-hex-indicator light code">#</div>
                        <input
                            type="text"
                            className="color-input text"
                            name="color"
                            size="10"
                            defaultValue={color}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ColorPicker;