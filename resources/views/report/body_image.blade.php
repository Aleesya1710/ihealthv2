
 <div class="bg-white rounded-lg p-4">
             <svg id="anatomy" viewBox="0 0 200 300" class="w-full h-auto bg-gray-100 rounded">
                <!-- Head -->
                <circle id="head" cx="100" cy="40" r="30" fill="{{ $painLocation === 'Head' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Chest -->
                <rect id="chest" x="70" y="80" width="60" height="80" fill="{{ $painLocation === 'Chest' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Left Arm -->
                <rect id="left-arm" x="30" y="80" width="30" height="100" fill="{{ $painLocation === 'Left Arm' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Right Arm -->
                <rect id="right-arm" x="140" y="80" width="30" height="100" fill="{{ $painLocation === 'Right Arm' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Left Leg -->
                <rect id="left-leg" x="70" y="180" width="25" height="100" fill="{{ $painLocation === 'Left Leg' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Right Leg -->
                <rect id="right-leg" x="105" y="180" width="25" height="100" fill="{{ $painLocation === 'Right Leg' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />
            </svg>
        </div>
