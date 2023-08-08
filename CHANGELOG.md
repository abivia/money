## 1.1.0

Added:
- Money::quantum($scale|null) returns the smallest significant number for calculation
for example quantum(2) returns a Money object with value '0.01'.

Changed:
- comp() now assumes comparison against zero if no argument provided.
- __get() explicitly returns mixed type (thanks @JaberWiki).


### 1.0.1

Bugs:

- Fixed error in rounding at scale 0.

## 1.0.0

- First production release
