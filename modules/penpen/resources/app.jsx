import React from 'react';
import { render } from 'react-dom';
import { getSprinklesComponent } from './sprinkles';

document.addEventListener('DOMContentLoaded', () => {
    const sprinkles = getSprinklesComponent();
    for (const sprinkle of sprinkles) {
        const { Comp, props, node } = sprinkle;
        render(<Comp {...props} />, node);
    }
});
